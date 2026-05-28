from datetime import datetime
from decimal import Decimal

from flask import Blueprint, request, send_file

from models.apartado import Apartado
from models.cliente import Cliente
from models.compra import Compra
from models.cotizacion import Cotizacion
from db import db
from models.movimiento_inventario import MovimientoInventario
from pdf_generator import generar_reporte_ventas_pdf
from models.producto import Producto
from models.proveedor import Proveedor
from models.reembolso import Reembolso
from models.usuario import Usuario
from models.venta import Venta

reportes_bp = Blueprint("reportes", __name__, url_prefix="/reportes")


@reportes_bp.get("/estadisticas")
def select_statistics_report():
    try:
        hoy = datetime.now().date()
        ventas_hoy = Venta.query.filter(
            db.func.date(Venta.fecha_creacion) == hoy
        ).count()

        productos_bajo_stock = Producto.query.filter(
            Producto.cantidad_disponible < 10
        ).count()

        total_clientes = Cliente.query.count()

        # Ventas (Totales y del mes)
        total_ventas = Venta.query.count()
        ventas_mes = Venta.query.filter(Venta.fecha_creacion >= inicio_mes).count()

        # Nuevos Contadores para Tarjetas de Módulos
        total_empleados = Usuario.query.count()
        total_proveedores = Proveedor.query.count()
        total_compras = Compra.query.count()
        total_apartados_activos = Apartado.query.filter_by(estado="activo").count()
        total_reembolsos = Reembolso.query.count()
        total_inventario_movs = MovimientoInventario.query.count()

        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        tasa_actual = (
            float(cotizacion_actual.tasa_dolar_bolivares) if cotizacion_actual else 0.0
        )

        return {
            "total_productos": total_productos,
            "stock_bajo": stock_bajo,
            "total_clientes": total_clientes,
            "total_ventas": total_ventas,
            "ventas_mes": ventas_mes,
            # Nuevos campos
            "total_empleados": total_empleados,
            "total_proveedores": total_proveedores,
            "total_compras": total_compras,
            "total_apartados_activos": total_apartados_activos,
            "total_reembolsos": total_reembolsos,
            "total_inventario": total_inventario_movs,
            "total_cotizacion": tasa_actual,
        }
    except Exception as e:
        return {"message": f"Error al obtener estadísticas: {str(e)}"}, 500


@reportes_bp.get("/ventas")
def select_sales_report():
    fecha_desde = request.args.get("desde")
    fecha_hasta = request.args.get("hasta")

    query = Venta.query

    if fecha_desde:
        query = query.filter(Venta.fecha_creacion >= fecha_desde)
    if fecha_hasta:
        query = query.filter(Venta.fecha_creacion <= fecha_hasta)

    ventas = query.order_by(Venta.fecha_creacion.desc()).all()

    reporte = []
    total_general = Decimal("0")

    for venta in ventas:
        total_venta = sum(
            d.precio_unitario_tipo_dolares * d.cantidad for d in venta.detalles
        )
        total_general += total_venta

        reporte.append(
            {
                "id": venta.id,
                "fecha": venta.fecha_creacion.strftime("%Y-%m-%d %H:%M"),
                "cliente": f"{venta.cliente.nombre} {venta.cliente.apellidos}",
                "total": float(total_venta),
            }
        )

    return {
        "ventas": reporte,
        "total_general": float(total_general),
        "cantidad_ventas": len(reporte),
    }


@reportes_bp.get("/ventas/pdf")
def select_sales_report_pdf():
    try:
        fecha_desde = request.args.get("desde")
        fecha_hasta = request.args.get("hasta")

        query = Venta.query

        if fecha_desde:
            query = query.filter(Venta.fecha_creacion >= fecha_desde)
        if fecha_hasta:
            query = query.filter(Venta.fecha_creacion <= fecha_hasta)

        ventas = query.order_by(Venta.fecha_creacion.desc()).all()
        ventas_data = [v.to_dict() for v in ventas]

        # Generar el PDF
        pdf_path = generar_reporte_ventas_pdf(ventas_data, fecha_desde, fecha_hasta)

        # Enviar el archivo
        return send_file(
            pdf_path,
            mimetype="application/pdf",
            as_attachment=True,
            download_name="reporte_ventas.pdf",
        )

    except Exception as e:
        return {"success": False, "message": f"Error al generar reporte: {str(e)}"}, 500
