from db import db


class TipoPago(db.Model):
    __tablename__ = "tipos_pago"

    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)
    nombre = db.Column(
        db.String(100), nullable=False
    )  # Ej: Efectivo, Transferencia, Tarjeta

    # Relaciones
    pagos = db.relationship("Pago", backref="tipo_pago", lazy=True)

    def to_dict(self):
        return {"id": self.id, "nombre": self.nombre}
