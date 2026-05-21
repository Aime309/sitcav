from db import db


class Sector(db.Model):
    __tablename__ = "sectores"

    id = db.Column(db.Integer, primary_key=True)
    id_localidad = db.Column(
        db.Integer, db.ForeignKey("localidades.id"), nullable=False
    )
    nombre = db.Column(db.String(100), nullable=False)

    # Relaciones
    proveedores = db.relationship("Proveedor", backref="sector", lazy=True)
    negocios = db.relationship("Negocio", backref="sector", lazy=True)
