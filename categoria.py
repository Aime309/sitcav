from db import db


class Categoria(db.Model):
    __tablename__ = "categorias"

    id = db.Column(db.Integer, primary_key=True)
    id_usuario = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=False)
    nombre = db.Column(db.String(100), unique=True, nullable=False)

    # Relaciones
    productos = db.relationship("Producto", backref="categoria", lazy=True)

    def to_dict(self):
        return {"id": self.id, "nombre": self.nombre}
