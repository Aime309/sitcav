from flask import Blueprint

from apartados import apartados_bp
from backup import backup_bp
from categorias import categorias_bp
from clientes import clientes_bp
from compras import compras_bp
from consultas import consultas_bp
from cotizacion import cotizacion_bp
from dashboard import dashboard_bp
from debug import debug_bp
from empleados import empleados_bp
from estadisticas import estadisticas_bp
from estados import estados_bp
from factura import factura_bp
from inventario import inventario_bp
from localidades import localidades_bp
from negocio import negocio_bp
from pagos import pagos_bp
from productos import productos_bp
from proveedores import proveedores_bp
from reembolsos import reembolsos_bp
from reportes import reportes_bp
from sectores import sectores_bp
from tipos_pago import tipos_pago_bp
from usuarios import usuarios_bp
from ventas import ventas_bp

api_bp = Blueprint("api", __name__, url_prefix="/api")

api_bp.register_blueprint(debug_bp)
api_bp.register_blueprint(dashboard_bp)
api_bp.register_blueprint(usuarios_bp)
api_bp.register_blueprint(clientes_bp)
api_bp.register_blueprint(categorias_bp)
api_bp.register_blueprint(productos_bp)
api_bp.register_blueprint(proveedores_bp)
api_bp.register_blueprint(ventas_bp)
api_bp.register_blueprint(compras_bp)
api_bp.register_blueprint(pagos_bp)
api_bp.register_blueprint(tipos_pago_bp)
api_bp.register_blueprint(estados_bp)
api_bp.register_blueprint(localidades_bp)
api_bp.register_blueprint(sectores_bp)
api_bp.register_blueprint(reportes_bp)
api_bp.register_blueprint(backup_bp)
api_bp.register_blueprint(negocio_bp)
api_bp.register_blueprint(factura_bp)
api_bp.register_blueprint(empleados_bp)
api_bp.register_blueprint(apartados_bp)
api_bp.register_blueprint(inventario_bp)
api_bp.register_blueprint(consultas_bp)
api_bp.register_blueprint(cotizacion_bp)
api_bp.register_blueprint(reembolsos_bp)
api_bp.register_blueprint(estadisticas_bp)
