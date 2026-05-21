from datetime import datetime

from flask import Blueprint, jsonify

from models import (
    Apartado,
    Cliente,
    Compra,
    Cotizacion,
    MovimientoInventario,
    Producto,
    Proveedor,
    Reembolso,
    Usuario,
    Venta,
)

dashboard_bp = Blueprint("dashboard", __name__, url_prefix="/dashboard")


@dashboard_bp.get("/stats")
def get_dashboard_stats():
    """Retorna estadísticas generales para el dashboard (contadores)"""
    try:
        hoy = datetime.now()
        inicio_mes = datetime(hoy.year, hoy.month, 1)

        # Datos Básicos
        total_productos = Producto.query.count()
        stock_bajo = Producto.query.filter(Producto.cantidad_disponible <= 5).count()
        total_clientes = Cliente.query.count()
        total_ventas = Venta.query.count()
        ventas_mes = Venta.query.filter(Venta.fecha_creacion >= inicio_mes).count()

        # Datos para Tarjetas de Módulos (Nuevos)
        total_empleados = Usuario.query.count()
        total_proveedores = Proveedor.query.count()
        total_compras = Compra.query.count()
        total_apartados_activos = Apartado.query.filter_by(estado="activo").count()
        total_reembolsos = Reembolso.query.count()
        total_inventario_movs = MovimientoInventario.query.count()

        # Tasa
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        tasa_actual = (
            float(cotizacion_actual.tasa_dolar_bolivares) if cotizacion_actual else 0.0
        )

        return jsonify(
            {
                "total_productos": total_productos,
                "stock_bajo": stock_bajo,
                "total_clientes": total_clientes,
                "total_ventas": total_ventas,
                "ventas_mes": ventas_mes,
                "total_empleados": total_empleados,
                "total_proveedores": total_proveedores,
                "total_compras": total_compras,
                "total_apartados_activos": total_apartados_activos,
                "total_reembolsos": total_reembolsos,
                "total_inventario": total_inventario_movs,
                "total_cotizacion": tasa_actual,
            }
        )
    except Exception as e:
        print(f"Error dashboard stats: {e}")
        return jsonify({"message": f"Error: {str(e)}", "total_productos": 0}), 500
