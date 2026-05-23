from db import db


class Localidad(db.Model):
    __tablename__ = "localidades"

    id = db.Column(db.Integer, primary_key=True)
    id_estado = db.Column(db.Integer, db.ForeignKey("estados.id"), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)

    # Relaciones
    sectores = db.relationship("Sector", backref="localidad", lazy=True)
    clientes = db.relationship("Cliente", backref="localidad", lazy=True)
    proveedores = db.relationship("Proveedor", backref="localidad", lazy=True)
    negocios = db.relationship("Negocio", backref="localidad", lazy=True)
