from db import db


class Cliente(db.Model):
    __tablename__ = "clientes"

    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(100), nullable=False)
    apellidos = db.Column(db.String(100), nullable=True)
    cedula = db.Column(db.String(20), unique=True, nullable=False)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey("localidades.id"), nullable=True)

    # Relaciones
    ventas = db.relationship("Venta", backref="cliente", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "nombre": self.nombre,
            "apellidos": self.apellidos,
            "cedula": self.cedula,
            "telefono": self.telefono,
            "direccion": self.direccion,
            "id_localidad": self.id_localidad,
        }
