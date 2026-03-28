"""
Modelos de Base de Datos - Sistema de Gestión Administrativo
Basado en el Diagrama Entidad-Relación del proyecto
"""

from datetime import datetime
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()

# Helper function to get local time instead of UTC
def local_now():
    """Returns current local time (not UTC) for database timestamps"""
    return datetime.now()

# =====================================================================
# MODELO: Usuarios (Empleados del sistema)
# =====================================================================
class Usuario(db.Model):
    __tablename__ = 'usuarios'
    
    id = db.Column(db.Integer, primary_key=True)
    cedula = db.Column(db.String(20), unique=True, nullable=False)
    contrasena = db.Column(db.String(255), nullable=False)  # Hasheada
    rol = db.Column(db.String(50), nullable=False)  # Vendedor, Empleado Superior, Encargado
    activo = db.Column(db.Boolean, default=True)
    
    # Profile fields
    nombre = db.Column(db.String(100), nullable=False)
    apellidos = db.Column(db.String(100), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
    foto_url = db.Column(db.String(500), nullable=True)  # Profile photo URL or local path
    
    # Preguntas de Seguridad (3)
    pregunta_1 = db.Column(db.String(255), nullable=True)
    respuesta_1 = db.Column(db.String(255), nullable=True)
    pregunta_2 = db.Column(db.String(255), nullable=True)
    respuesta_2 = db.Column(db.String(255), nullable=True)
    pregunta_3 = db.Column(db.String(255), nullable=True)
    respuesta_3 = db.Column(db.String(255), nullable=True)
    
    admin_id = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=True)
    
    # Relaciones
    administrados = db.relationship('Usuario', backref=db.backref('administrador', remote_side=[id]))
    categorias = db.relationship('Categoria', backref='creador', lazy=True)
    tipos_pago = db.relationship('TipoPago', backref='creador', lazy=True)
    cotizaciones = db.relationship('Cotizacion', backref='creador', lazy=True)
    estados = db.relationship('Estado', backref='creador', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'cedula': self.cedula,
            'nombre': self.nombre,
            'apellidos': self.apellidos,
            'direccion': self.direccion,
            'foto_url': self.foto_url,
            'rol': self.rol,
            'activo': self.activo,
            'pregunta_1': self.pregunta_1,
            'pregunta_2': self.pregunta_2,
            'pregunta_3': self.pregunta_3
        }

# =====================================================================
# MODELO: Estados, Localidades, Sectores (Ubicación geográfica)
# =====================================================================
class Estado(db.Model):
    __tablename__ = 'estados'
    
    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)
    
    # Relaciones
    localidades = db.relationship('Localidad', backref='estado', lazy=True)
    proveedores = db.relationship('Proveedor', backref='estado', lazy=True)

class Localidad(db.Model):
    __tablename__ = 'localidades'
    
    id = db.Column(db.Integer, primary_key=True)
    id_estado = db.Column(db.Integer, db.ForeignKey('estados.id'), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)
    
    # Relaciones
    sectores = db.relationship('Sector', backref='localidad', lazy=True)
    clientes = db.relationship('Cliente', backref='localidad', lazy=True)
    proveedores = db.relationship('Proveedor', backref='localidad', lazy=True)
    negocios = db.relationship('Negocio', backref='localidad', lazy=True)

class Sector(db.Model):
    __tablename__ = 'sectores'
    
    id = db.Column(db.Integer, primary_key=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey('localidades.id'), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)
    
    # Relaciones
    proveedores = db.relationship('Proveedor', backref='sector', lazy=True)
    negocios = db.relationship('Negocio', backref='sector', lazy=True)

# =====================================================================
# MODELO: Negocios (Datos de la empresa)
# =====================================================================
class Negocio(db.Model):
    __tablename__ = 'negocios'
    
    id = db.Column(db.Integer, primary_key=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey('localidades.id'), nullable=False)
    id_sector = db.Column(db.Integer, db.ForeignKey('sectores.id'), nullable=False)
    nombre = db.Column(db.String(200), nullable=False)
    rif = db.Column(db.String(50), nullable=True)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)

# =====================================================================
# MODELO: Clientes
# =====================================================================
class Cliente(db.Model):
    __tablename__ = 'clientes'
    
    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(100), nullable=False)
    apellidos = db.Column(db.String(100), nullable=True)
    cedula = db.Column(db.String(20), unique=True, nullable=False)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey('localidades.id'), nullable=True)
    
    # Relaciones
    ventas = db.relationship('Venta', backref='cliente', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'nombre': self.nombre,
            'apellidos': self.apellidos,
            'cedula': self.cedula,
            'telefono': self.telefono,
            'direccion': self.direccion,
            'id_localidad': self.id_localidad
        }

# =====================================================================
# MODELO: Categorías de Productos
# =====================================================================
class Categoria(db.Model):
    __tablename__ = 'categorias'
    
    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=False)
    nombre = db.Column(db.String(100), unique=True, nullable=False)
    
    # Relaciones
    productos = db.relationship('Producto', backref='categoria', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'nombre': self.nombre
        }

# =====================================================================
# MODELO: Proveedores
# =====================================================================
class Proveedor(db.Model):
    __tablename__ = 'proveedores'
    
    id = db.Column(db.Integer, primary_key=True)
    id_estado = db.Column(db.Integer, db.ForeignKey('estados.id'), nullable=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey('localidades.id'), nullable=True)
    id_sector = db.Column(db.Integer, db.ForeignKey('sectores.id'), nullable=True)
    nombre = db.Column(db.String(200), nullable=False)
    rif = db.Column(db.String(50), nullable=True)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
    
    # Relaciones
    productos = db.relationship('Producto', backref='proveedor', lazy=True)
    compras = db.relationship('Compra', backref='proveedor', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'nombre': self.nombre,
            'rif': self.rif,
            'telefono': self.telefono,
            'direccion': self.direccion
        }

# =====================================================================
# MODELO: Productos
# =====================================================================
class Producto(db.Model):
    """Modelo para la tabla de Productos."""
    __tablename__ = 'productos'
    
    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(200), nullable=False)
    descripcion = db.Column(db.Text)
    codigo = db.Column(db.String(100), unique=True, nullable=False)
    imei = db.Column(db.String(50), nullable=True)  # IMEI del dispositivo
    id_categoria = db.Column(db.Integer, db.ForeignKey('categorias.id'), nullable=False)
    id_proveedor = db.Column(db.Integer, db.ForeignKey('proveedores.id'))
    precio_unitario_actual_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad_disponible = db.Column(db.Integer, default=0)
    dias_garantia = db.Column(db.Integer, default=0)
    dias_apartado = db.Column(db.Integer, default=0)
    imagen_url = db.Column(db.String(500))  # Campo para URL de imagen del producto
    
    # Relaciones
    detalles_ventas = db.relationship('DetalleVenta', backref='producto')
    detalles_compras = db.relationship('DetalleCompra', backref='producto')
    
    def to_dict(self):
        return {
            'id': self.id,
            'nombre': self.nombre,
            'descripcion': self.descripcion,
            'codigo': self.codigo,
            'imei': self.imei,
            'id_categoria': self.id_categoria,
            'id_proveedor': self.id_proveedor,
            'precio_unitario_actual_dolares': str(self.precio_unitario_actual_dolares),
            'cantidad_disponible': self.cantidad_disponible,
            'dias_garantia': self.dias_garantia,
            'dias_apartado': self.dias_apartado,
            'imagen_url': self.imagen_url
        }

# =====================================================================
# MODELO: Ventas
# =====================================================================
class Venta(db.Model):
    __tablename__ = 'ventas'
    
    id = db.Column(db.Integer, primary_key=True)
    id_cliente = db.Column(db.Integer, db.ForeignKey('clientes.id'), nullable=False)
    id_vendedor = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=True)
    fecha_creacion = db.Column(db.DateTime, default=local_now, nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2), default=0)
    
    # Relaciones
    detalles = db.relationship('DetalleVenta', backref='venta', lazy=True, cascade='all, delete-orphan')
    reembolsos = db.relationship('Reembolso', backref='venta', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_cliente': self.id_cliente,
            'id_vendedor': self.id_vendedor,
            'cliente': self.cliente.to_dict() if self.cliente else None,
            'fecha_creacion': self.fecha_creacion.strftime('%Y-%m-%d %H:%M:%S.%f'),
            'cotizacion_dolar_bolivares': float(self.cotizacion_dolar_bolivares) if self.cotizacion_dolar_bolivares else 0,
            'detalles': [d.to_dict_with_product() for d in self.detalles]
        }

# =====================================================================
# MODELO: Reembolsos
# =====================================================================
class Reembolso(db.Model):
    __tablename__ = 'reembolsos'
    
    id = db.Column(db.Integer, primary_key=True)
    id_venta = db.Column(db.Integer, db.ForeignKey('ventas.id'), nullable=False)
    id_usuario = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=False)
    monto_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    monto_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    tasa_cambio = db.Column(db.Numeric(10, 2), nullable=False)
    motivo = db.Column(db.String(255), nullable=True)
    fecha = db.Column(db.DateTime, default=local_now, nullable=False)
    
    # Relaciones
    usuario = db.relationship('Usuario', backref='reembolsos_procesados', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_venta': self.id_venta,
            'id_usuario': self.id_usuario,
            'usuario_nombre': self.usuario.nombre if self.usuario else 'Desconocido',
            'monto_dolares': float(self.monto_dolares),
            'monto_bolivares': float(self.monto_bolivares),
            'tasa_cambio': float(self.tasa_cambio),
            'motivo': self.motivo,
            'fecha': self.fecha.strftime('%Y-%m-%d %H:%M:%S')
        }

# =====================================================================
# MODELO: Detalles de Ventas
# =====================================================================
class DetalleVenta(db.Model):
    __tablename__ = 'detalles_ventas'
    
    id = db.Column(db.Integer, primary_key=True)
    id_venta = db.Column(db.Integer, db.ForeignKey('ventas.id'), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey('productos.id'), nullable=False)
    precio_unitario_tipo_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)
    esta_apartado = db.Column(db.Boolean, default=False)
    
    # Relaciones
    pagos = db.relationship('Pago', backref='detalle_venta', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_producto': self.id_producto,
            'cantidad': self.cantidad,
            'precio_unitario_tipo_dolares': str(self.precio_unitario_tipo_dolares),
            'esta_apartado': self.esta_apartado
        }
    
    def to_dict_with_product(self):
        """Versión extendida que incluye información del producto"""
        result = self.to_dict()
        if self.producto:
            result['producto'] = self.producto.to_dict()
        return result

# =====================================================================
# MODELO: Compras
# =====================================================================
class Compra(db.Model):
    __tablename__ = 'compras'
    
    id = db.Column(db.Integer, primary_key=True)
    id_proveedor = db.Column(db.Integer, db.ForeignKey('proveedores.id'), nullable=False)
    fecha_creacion = db.Column(db.DateTime, default=local_now, nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    
    # Relaciones
    detalles = db.relationship('DetalleCompra', backref='compra', lazy=True, cascade='all, delete-orphan')
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_proveedor': self.id_proveedor,
            'proveedor': self.proveedor.to_dict() if self.proveedor else None,
            'fecha_creacion': self.fecha_creacion.strftime('%Y-%m-%d %H:%M:%S'),
            'cotizacion_dolar_bolivares': float(self.cotizacion_dolar_bolivares),
            'detalles': [d.to_dict() for d in self.detalles]
        }

# =====================================================================
# MODELO: Detalles de Compras
# =====================================================================
class DetalleCompra(db.Model):
    __tablename__ = 'detalles_compras'
    
    id = db.Column(db.Integer, primary_key=True)
    id_compra = db.Column(db.Integer, db.ForeignKey('compras.id'), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey('productos.id'), nullable=False)
    precio_unitario_tipo_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_producto': self.id_producto,
            'cantidad': self.cantidad,
            'precio_unitario': float(self.precio_unitario_tipo_dolares),
            'subtotal': float(self.precio_unitario_tipo_dolares * self.cantidad)
        }

# =====================================================================
# MODELO: Tipos de Pago
# =====================================================================
class TipoPago(db.Model):
    __tablename__ = 'tipos_pago'
    
    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)  # Ej: Efectivo, Transferencia, Tarjeta
    
    # Relaciones
    pagos = db.relationship('Pago', backref='tipo_pago', lazy=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'nombre': self.nombre
        }

# =====================================================================
# MODELO: Pagos
# =====================================================================
class Pago(db.Model):
    __tablename__ = 'pagos'
    
    id = db.Column(db.Integer, primary_key=True)
    id_tipo_pago = db.Column(db.Integer, db.ForeignKey('tipos_pago.id'), nullable=False)
    id_detalle_venta = db.Column(db.Integer, db.ForeignKey('detalles_ventas.id'), nullable=False)
    fecha_creacion = db.Column(db.DateTime, default=local_now, nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    monto = db.Column(db.Numeric(10, 2), nullable=False)  # En dólares o bolívares según config
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_tipo_pago': self.id_tipo_pago,
            'id_detalle_venta': self.id_detalle_venta,
            'fecha_creacion': self.fecha_creacion.strftime('%Y-%m-%d %H:%M:%S'),
            'monto': float(self.monto),
            'cotizacion_dolar_bolivares': float(self.cotizacion_dolar_bolivares)
        }

# =====================================================================
# MODELO: Cotizaciones (Tasa del dólar)
# =====================================================================
class Cotizacion(db.Model):
    __tablename__ = 'cotizaciones'
    
    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey('usuarios.id'), nullable=False)
    fecha_hora = db.Column(db.DateTime, default=local_now, nullable=False)
    tasa_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    
    def to_dict(self):
        return {
            'id': self.id,
            'fecha_hora': self.fecha_hora.strftime('%Y-%m-%d %H:%M:%S'),
            'tasa_dolar_bolivares': float(self.tasa_dolar_bolivares)
        }


# =====================================================================
# MODELO: Apartados (Sistema de Layaway - Reservas con abonos)
# =====================================================================
class Apartado(db.Model):
    __tablename__ = 'apartados'
    
    id = db.Column(db.Integer, primary_key=True)
    id_cliente = db.Column(db.Integer, db.ForeignKey('clientes.id'), nullable=False)
    fecha_creacion = db.Column(db.DateTime, default=local_now, nullable=False)
    fecha_limite = db.Column(db.DateTime, nullable=False)  # Generalmente 3 meses
    monto_total = db.Column(db.Numeric(10, 2), nullable=False)
    monto_pagado = db.Column(db.Numeric(10, 2), default=0)
    estado = db.Column(db.String(20), default='activo')  # activo, completado, cancelado
    observaciones = db.Column(db.Text, nullable=True)
    
    # Relaciones
    cliente = db.relationship('Cliente', backref=db.backref('apartados', lazy=True))
    detalles = db.relationship('DetalleApartado', backref='apartado', lazy=True, cascade='all, delete-orphan')
    pagos = db.relationship('PagoApartado', backref='apartado', lazy=True, cascade='all, delete-orphan')
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_cliente': self.id_cliente,
            'cliente': self.cliente.to_dict() if self.cliente else None,
            'fecha_creacion': self.fecha_creacion.strftime('%Y-%m-%d %H:%M:%S'),
            'fecha_limite': self.fecha_limite.strftime('%Y-%m-%d %H:%M:%S'),
            'monto_total': float(self.monto_total),
            'monto_pagado': float(self.monto_pagado),
            'monto_pendiente': float(self.monto_total) - float(self.monto_pagado),
            'estado': self.estado,
            'observaciones': self.observaciones,
            'detalles': [d.to_dict() for d in self.detalles],
            'pagos': [p.to_dict() for p in self.pagos]
        }


# =====================================================================
# MODELO: Detalles de Apartados (Productos en un apartado)
# =====================================================================
class DetalleApartado(db.Model):
    __tablename__ = 'detalles_apartados'
    
    id = db.Column(db.Integer, primary_key=True)
    id_apartado = db.Column(db.Integer, db.ForeignKey('apartados.id'), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey('productos.id'), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)
    precio_unitario = db.Column(db.Numeric(10, 2), nullable=False)
    
    # Relación con producto
    producto = db.relationship('Producto', backref=db.backref('detalles_apartados', lazy=True))
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_producto': self.id_producto,
            'producto': self.producto.to_dict() if self.producto else None,
            'cantidad': self.cantidad,
            'precio_unitario': float(self.precio_unitario),
            'subtotal': float(self.precio_unitario) * self.cantidad
        }


# =====================================================================
# MODELO: Pagos de Apartados (Abonos)
# =====================================================================
class PagoApartado(db.Model):
    __tablename__ = 'pagos_apartados'
    
    id = db.Column(db.Integer, primary_key=True)
    id_apartado = db.Column(db.Integer, db.ForeignKey('apartados.id'), nullable=False)
    monto = db.Column(db.Numeric(10, 2), nullable=False)
    fecha_pago = db.Column(db.DateTime, default=local_now, nullable=False)
    observacion = db.Column(db.String(255), nullable=True)
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_apartado': self.id_apartado,
            'monto': float(self.monto),
            'fecha_pago': self.fecha_pago.strftime('%Y-%m-%d %H:%M:%S'),
            'observacion': self.observacion
        }


# =====================================================================
# MODELO: Movimientos de Inventario (Historial de entradas/salidas)
# =====================================================================
class MovimientoInventario(db.Model):
    __tablename__ = 'movimientos_inventario'
    
    id = db.Column(db.Integer, primary_key=True)
    id_producto = db.Column(db.Integer, db.ForeignKey('productos.id'), nullable=False)
    tipo = db.Column(db.String(20), nullable=False)  # entrada, salida, ajuste
    cantidad = db.Column(db.Integer, nullable=False)
    motivo = db.Column(db.String(50), nullable=False)  # venta, apartado, compra, devolucion, ajuste_manual
    referencia_id = db.Column(db.Integer, nullable=True)  # ID de venta/apartado/compra relacionada
    referencia_tipo = db.Column(db.String(20), nullable=True)  # venta, apartado, compra
    fecha = db.Column(db.DateTime, default=local_now, nullable=False)
    observacion = db.Column(db.String(255), nullable=True)
    
    # Relación con producto
    producto = db.relationship('Producto', backref=db.backref('movimientos_inventario', lazy=True))
    
    def to_dict(self):
        return {
            'id': self.id,
            'id_producto': self.id_producto,
            'producto': self.producto.to_dict() if self.producto else None,
            'tipo': self.tipo,
            'cantidad': self.cantidad,
            'motivo': self.motivo,
            'referencia_id': self.referencia_id,
            'referencia_tipo': self.referencia_tipo,
            'fecha': self.fecha.strftime('%Y-%m-%d %H:%M:%S'),
            'observacion': self.observacion
        }
