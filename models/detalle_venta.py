from db import db


class DetalleVenta(db.Model):
    __tablename__ = "detalles_ventas"

    id = db.Column(db.Integer, primary_key=True)
    id_venta = db.Column(db.Integer, db.ForeignKey("ventas.id"), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey("productos.id"), nullable=False)
    precio_unitario_tipo_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)
    esta_apartado = db.Column(db.Boolean)

    # Relaciones
    pagos = db.relationship("Pago", backref="detalle_venta", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "id_producto": self.id_producto,
            "cantidad": self.cantidad,
            "precio_unitario_tipo_dolares": str(self.precio_unitario_tipo_dolares),
            "esta_apartado": self.esta_apartado,
        }

    def to_dict_with_product(self):
        result = self.to_dict()
        if self.producto:
            result["producto"] = self.producto.to_dict()
        return result
