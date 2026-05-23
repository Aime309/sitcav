from db import db


class Producto(db.Model):
    __tablename__ = "productos"

    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(200), nullable=False)
    descripcion = db.Column(db.Text)
    codigo = db.Column(db.String(100), unique=True, nullable=False)
    imei = db.Column(db.String(50), nullable=True)  # IMEI del dispositivo
    id_categoria = db.Column(db.Integer, db.ForeignKey("categorias.id"), nullable=False)
    id_proveedor = db.Column(db.Integer, db.ForeignKey("proveedores.id"))
    precio_unitario_actual_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad_disponible = db.Column(db.Integer)
    dias_garantia = db.Column(db.Integer)
    dias_apartado = db.Column(db.Integer)
    imagen_url = db.Column(db.String(500))  # Campo para URL de imagen del producto

    # Relaciones
    detalles_ventas = db.relationship("DetalleVenta", backref="producto")
    detalles_compras = db.relationship("DetalleCompra", backref="producto")

    def to_dict(self):
        return {
            "id": self.id,
            "nombre": self.nombre,
            "descripcion": self.descripcion,
            "codigo": self.codigo,
            "imei": self.imei,
            "id_categoria": self.id_categoria,
            "id_proveedor": self.id_proveedor,
            "precio_unitario_actual_dolares": str(self.precio_unitario_actual_dolares),
            "cantidad_disponible": self.cantidad_disponible,
            "dias_garantia": self.dias_garantia,
            "dias_apartado": self.dias_apartado,
            "imagen_url": self.imagen_url,
        }
