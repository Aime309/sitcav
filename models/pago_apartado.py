from db import db, local_now


class PagoApartado(db.Model):
    __tablename__ = "pagos_apartados"

    id = db.Column(db.Integer, primary_key=True)
    id_apartado = db.Column(db.Integer, db.ForeignKey("apartados.id"), nullable=False)
    monto = db.Column(db.Numeric(10, 2), nullable=False)
    fecha_pago = db.Column(db.DateTime, default=local_now, nullable=False)
    observacion = db.Column(db.String(255), nullable=True)

    def to_dict(self):
        return {
            "id": self.id,
            "id_apartado": self.id_apartado,
            "monto": float(self.monto),
            "fecha_pago": self.fecha_pago.strftime("%Y-%m-%d %H:%M:%S"),
            "observacion": self.observacion,
        }
