from datetime import datetime, timedelta

from flask import Blueprint

from models import Apartado, Categoria, Compra, DetalleVenta, Producto, Venta, db

estadisticas_bp = Blueprint("estadisticas", __name__, url_prefix="/estadisticas")


@estadisticas_bp.get("/resumen")
def get_estadisticas_resumen():
    try:
        hoy = datetime.now().date()
        inicio_dia = datetime.combine(hoy, datetime.min.time())
        fin_dia = datetime.combine(hoy, datetime.max.time())

        # 1. Ventas de Hoy
        ventas_hoy = Venta.query.filter(
            Venta.fecha_creacion.between(inicio_dia, fin_dia)
        ).all()
        total_ventas_hoy = sum(
            sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles)
            for v in ventas_hoy
        )
        cantidad_ventas_hoy = len(ventas_hoy)

        # 2. Compras de Hoy (Gastos)
        compras_hoy = Compra.query.filter(
            Compra.fecha_creacion.between(inicio_dia, fin_dia)
        ).all()
        # Fix: use precio_unitario_tipo_dolares instead of precio_unitario
        total_compras_hoy = sum(
            sum(d.precio_unitario_tipo_dolares * d.cantidad for d in c.detalles)
            for c in compras_hoy
        )

        # 3. Productos Stock Bajo
        stock_bajo = Producto.query.filter(Producto.cantidad_disponible <= 5).count()

        # 4. Beneficio Estimado (Ventas - Compras)
        beneficio_hoy = total_ventas_hoy - total_compras_hoy

        # 5. Apartados Activos
        apartados_activos = Apartado.query.filter_by(estado="activo").count()

        return {
            "ventas_hoy_monto": float(total_ventas_hoy),
            "ventas_hoy_cantidad": cantidad_ventas_hoy,
            "compras_hoy_monto": float(total_compras_hoy),
            "stock_bajo_count": stock_bajo,
            "beneficio_hoy": float(beneficio_hoy),
            "apartados_activos": apartados_activos,
        }
    except Exception as e:
        return {"message": f"Error calculando resumen: {str(e)}", "success": False}, 500


@estadisticas_bp.get("/historico")
def get_estadisticas_historico():
    try:
        hoy = datetime.now().date()
        dias = []
        ventas_data = []
        compras_data = []

        # Datos para gráfica lineal (Últimos 7 días)
        for i in range(6, -1, -1):
            fecha = hoy - timedelta(days=i)
            inicio = datetime.combine(fecha, datetime.min.time())
            fin = datetime.combine(fecha, datetime.max.time())

            # Ventas del día
            ventas = Venta.query.filter(Venta.fecha_creacion.between(inicio, fin)).all()
            total_v = sum(
                sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles)
                for v in ventas
            )

            # Compras del día
            compras = Compra.query.filter(
                Compra.fecha_creacion.between(inicio, fin)
            ).all()
            # Fix: use precio_unitario_tipo_dolares instead of precio_unitario
            total_c = sum(
                sum(d.precio_unitario_tipo_dolares * d.cantidad for d in c.detalles)
                for c in compras
            )

            dias.append(fecha.strftime("%d/%m"))
            ventas_data.append(float(total_v))
            compras_data.append(float(total_c))

        # Top 5 Productos más vendidos
        from sqlalchemy import func

        # Note: We must handle cases where join returns no rows, resulting in None sums if not grouping correctly,
        # but group_by usually filters out null groups unless outer joined.
        # Here we use inner joins, so we are safe from null products, but sum could be null? No, quantity is non-nullable.

        top_productos_query = (
            db.session.query(
                Producto.nombre,
                func.sum(DetalleVenta.cantidad).label("total_vendido"),
            )
            .join(DetalleVenta)
            .group_by(Producto.id)
            .order_by(func.sum(DetalleVenta.cantidad).desc())
            .limit(5)
            .all()
        )

        top_productos = {
            "labels": [p[0] for p in top_productos_query],
            "data": [int(p[1] or 0) for p in top_productos_query],
        }

        # Ventas por Categoría
        ventas_categoria_query = (
            db.session.query(
                Categoria.nombre, func.sum(DetalleVenta.cantidad).label("total")
            )
            .join(Producto, Categoria.id == Producto.id_categoria)
            .join(DetalleVenta, Producto.id == DetalleVenta.id_producto)
            .group_by(Categoria.id)
            .all()
        )

        ventas_por_categoria = {
            "labels": [c[0] for c in ventas_categoria_query],
            "data": [int(c[1] or 0) for c in ventas_categoria_query],
        }

        return {
            "fechas": dias,
            "ventas": ventas_data,
            "compras": compras_data,
            "top_productos": top_productos,
            "ventas_por_categoria": ventas_por_categoria,
        }

    except Exception as e:
        print(f"Error en historico: {str(e)}")
        import traceback

        traceback.print_exc()
        return {
            "message": f"Error calculando histórico: {str(e)}",
            "success": False,
        }, 500
