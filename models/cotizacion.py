from db import db


class Cotizacion(db.Model):
    __tablename__ = "cotizaciones"

    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)
    fecha_hora = db.Column(db.DateTime, nullable=False)
    tasa_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)

    def to_dict(self):
        return {
            "id": self.id,
            "fecha_hora": self.fecha_hora.strftime("%Y-%m-%d %H:%M:%S"),
            "tasa_dolar_bolivares": float(self.tasa_dolar_bolivares),
        }
