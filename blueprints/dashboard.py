from flask import Blueprint

from models.apartado import Apartado
from models.cliente import Cliente
from models.compra import Compra
from models.cotizacion import Cotizacion
from models.movimiento_inventario import MovimientoInventario
from models.producto import Producto
from models.proveedor import Proveedor
from models.reembolso import Reembolso
from models.usuario import Usuario
from models.venta import Venta

dashboard_bp = Blueprint(
    name="dashboard", import_name=__name__, url_prefix="/dashboard"
)


@dashboard_bp.get("/stats")
def select_dashboard_stats():
    try:
        # Datos Básicos
        total_productos = Producto.query.count()
        stock_bajo = Producto.obtener_cantidad_productos_bajo_stock()
        total_clientes = Cliente.query.count()
        total_ventas = Venta.query.count()
        ventas_mes = Venta.obtener_cantidad_ventas_mes()

        # Datos para Tarjetas de Módulos (Nuevos)
        total_empleados = Usuario.query.count()
        total_proveedores = Proveedor.query.count()
        total_compras = Compra.query.count()
        total_apartados_activos = Apartado.obtener_cantidad_apartados_activos()
        total_reembolsos = Reembolso.query.count()
        total_inventario_movs = MovimientoInventario.query.count()

        # Tasa
        tasa_actual = Cotizacion.obtener_tasa_actual()

        return {
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
    except Exception as exception:
        return {"message": f"Error: {exception}", "total_productos": 0}, 500
