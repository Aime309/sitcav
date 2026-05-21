from db import db


class Negocio(db.Model):
    __tablename__ = "negocios"

    id = db.Column(db.Integer, primary_key=True)
    id_localidad = db.Column(
        db.Integer, db.ForeignKey("localidades.id"), nullable=False
    )
    id_sector = db.Column(db.Integer, db.ForeignKey("sectores.id"), nullable=False)
    nombre = db.Column(db.String(200), nullable=False)
    rif = db.Column(db.String(50), nullable=True)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
