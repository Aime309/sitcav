from db import db


class Proveedor(db.Model):
    __tablename__ = "proveedores"

    id = db.Column(db.Integer, primary_key=True)
    id_estado = db.Column(db.Integer, db.ForeignKey("estados.id"), nullable=True)
    id_localidad = db.Column(db.Integer, db.ForeignKey("localidades.id"), nullable=True)
    id_sector = db.Column(db.Integer, db.ForeignKey("sectores.id"), nullable=True)
    nombre = db.Column(db.String(200), nullable=False)
    rif = db.Column(db.String(50), nullable=True)
    telefono = db.Column(db.String(20), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)

    # Relaciones
    productos = db.relationship("Producto", backref="proveedor", lazy=True)
    compras = db.relationship("Compra", backref="proveedor", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "nombre": self.nombre,
            "rif": self.rif,
            "telefono": self.telefono,
            "direccion": self.direccion,
        }
