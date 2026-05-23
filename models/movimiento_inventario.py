from db import db


class MovimientoInventario(db.Model):
    __tablename__ = "movimientos_inventario"

    id = db.Column(db.Integer, primary_key=True)
    id_producto = db.Column(db.Integer, db.ForeignKey("productos.id"), nullable=False)
    tipo = db.Column(db.String(20), nullable=False)  # entrada, salida, ajuste
    cantidad = db.Column(db.Integer, nullable=False)
    motivo = db.Column(
        db.String(50), nullable=False
    )  # venta, apartado, compra, devolucion, ajuste_manual
    referencia_id = db.Column(
        db.Integer, nullable=True
    )  # ID de venta/apartado/compra relacionada
    referencia_tipo = db.Column(db.String(20), nullable=True)  # venta, apartado, compra
    fecha = db.Column(db.DateTime, server_default=db.func.current_timestamp(), nullable=False)
    observacion = db.Column(db.String(255), nullable=True)

    # Relación con producto
    producto = db.relationship(
        "Producto", backref=db.backref("movimientos_inventario", lazy=True)
    )

    def to_dict(self):
        return {
            "id": self.id,
            "id_producto": self.id_producto,
            "producto": self.producto.to_dict() if self.producto else None,
            "tipo": self.tipo,
            "cantidad": self.cantidad,
            "motivo": self.motivo,
            "referencia_id": self.referencia_id,
            "referencia_tipo": self.referencia_tipo,
            "fecha": self.fecha.strftime("%Y-%m-%d %H:%M:%S"),
            "observacion": self.observacion,
        }
