from db import db


class Estado(db.Model):
    __tablename__ = "estados"

    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)

    # Relaciones
    localidades = db.relationship("Localidad", backref="estado", lazy=True)
    proveedores = db.relationship("Proveedor", backref="estado", lazy=True)
