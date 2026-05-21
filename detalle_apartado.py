from db import db


class DetalleApartado(db.Model):
    __tablename__ = "detalles_apartados"

    id = db.Column(db.Integer, primary_key=True)
    id_apartado = db.Column(db.Integer, db.ForeignKey("apartados.id"), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey("productos.id"), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)
    precio_unitario = db.Column(db.Numeric(10, 2), nullable=False)

    # Relación con producto
    producto = db.relationship(
        "Producto", backref=db.backref("detalles_apartados", lazy=True)
    )

    def to_dict(self):
        return {
            "id": self.id,
            "id_producto": self.id_producto,
            "producto": self.producto.to_dict() if self.producto else None,
            "cantidad": self.cantidad,
            "precio_unitario": float(self.precio_unitario),
            "subtotal": float(self.precio_unitario) * self.cantidad,
        }
