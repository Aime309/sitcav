from db import db


class DetalleCompra(db.Model):
    __tablename__ = "detalles_compras"

    id = db.Column(db.Integer, primary_key=True)
    id_compra = db.Column(db.Integer, db.ForeignKey("compras.id"), nullable=False)
    id_producto = db.Column(db.Integer, db.ForeignKey("productos.id"), nullable=False)
    precio_unitario_tipo_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    cantidad = db.Column(db.Integer, nullable=False)

    def to_dict(self):
        return {
            "id": self.id,
            "id_producto": self.id_producto,
            "cantidad": self.cantidad,
            "precio_unitario": float(self.precio_unitario_tipo_dolares),
            "subtotal": float(self.precio_unitario_tipo_dolares * self.cantidad),
        }
