from db import db


class Reembolso(db.Model):
    __tablename__ = "reembolsos"

    id = db.Column(db.Integer, primary_key=True)
    id_venta = db.Column(db.Integer, db.ForeignKey("ventas.id"), nullable=False)
    id_usuario = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)
    monto_dolares = db.Column(db.Numeric(10, 2), nullable=False)
    monto_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    tasa_cambio = db.Column(db.Numeric(10, 2), nullable=False)
    motivo = db.Column(db.String(255), nullable=True)
    fecha = db.Column(db.DateTime, nullable=False)

    # Relaciones
    usuario = db.relationship("Usuario", backref="reembolsos_procesados", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "id_venta": self.id_venta,
            "id_usuario": self.id_usuario,
            "usuario_nombre": self.usuario.nombre if self.usuario else "Desconocido",
            "monto_dolares": float(self.monto_dolares),
            "monto_bolivares": float(self.monto_bolivares),
            "tasa_cambio": float(self.tasa_cambio),
            "motivo": self.motivo,
            "fecha": self.fecha.strftime("%Y-%m-%d %H:%M:%S"),
        }
