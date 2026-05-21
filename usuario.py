from db import db


class Usuario(db.Model):
    __tablename__ = "usuarios"

    id = db.Column(db.Integer, primary_key=True)
    cedula = db.Column(db.String(20), unique=True, nullable=False)
    contrasena = db.Column(db.String(255), nullable=False)  # Hasheada
    rol = db.Column(
        db.String(50), nullable=False
    )  # Vendedor, Empleado Superior, Encargado
    activo = db.Column(db.Boolean, default=True)

    # Profile fields
    nombre = db.Column(db.String(100), nullable=False)
    apellidos = db.Column(db.String(100), nullable=True)
    direccion = db.Column(db.String(300), nullable=True)
    foto_url = db.Column(
        db.String(500), nullable=True
    )  # Profile photo URL or local path

    # Preguntas de Seguridad (3)
    pregunta_1 = db.Column(db.String(255), nullable=True)
    respuesta_1 = db.Column(db.String(255), nullable=True)
    pregunta_2 = db.Column(db.String(255), nullable=True)
    respuesta_2 = db.Column(db.String(255), nullable=True)
    pregunta_3 = db.Column(db.String(255), nullable=True)
    respuesta_3 = db.Column(db.String(255), nullable=True)

    admin_id = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=True)

    # Relaciones
    administrados = db.relationship(
        "Usuario", backref=db.backref("administrador", remote_side=[id])
    )
    categorias = db.relationship("Categoria", backref="creador", lazy=True)
    tipos_pago = db.relationship("TipoPago", backref="creador", lazy=True)
    cotizaciones = db.relationship("Cotizacion", backref="creador", lazy=True)
    estados = db.relationship("Estado", backref="creador", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "cedula": self.cedula,
            "nombre": self.nombre,
            "apellidos": self.apellidos,
            "direccion": self.direccion,
            "foto_url": self.foto_url,
            "rol": self.rol,
            "activo": self.activo,
            "pregunta_1": self.pregunta_1,
            "pregunta_2": self.pregunta_2,
            "pregunta_3": self.pregunta_3,
        }
