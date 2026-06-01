from typing import Literal, TypedDict

from sqlalchemy.orm import Mapped, mapped_column
from werkzeug.security import check_password_hash, generate_password_hash

from db import db

Rol = Literal["Encargado"] | Literal["Empleado"]


class UsuarioDict(TypedDict):
    id: int
    cedula: str
    nombre: str
    nombres: str
    apellidos: str | None
    direccion: str | None
    foto_url: str | None
    rol: Rol
    activo: bool
    pregunta_1: str | None
    pregunta_2: str | None
    pregunta_3: str | None


class Usuario(db.Model):
    __tablename__: str = "usuarios"

    id: Mapped[int] = mapped_column(db.Integer, primary_key=True)
    cedula: Mapped[str] = mapped_column(db.String, unique=True, nullable=False)
    contrasena: Mapped[str] = mapped_column(db.String, nullable=False)
    rol: Mapped[Rol] = mapped_column(db.String, nullable=False)
    activo: Mapped[bool] = mapped_column(db.Boolean, nullable=False)

    # Profile fields
    nombre: Mapped[str] = mapped_column(db.String, nullable=False)
    apellidos: Mapped[str | None] = mapped_column(db.String, nullable=True)
    direccion: Mapped[str | None] = mapped_column(db.String, nullable=True)
    foto_url: Mapped[str | None] = mapped_column(db.String, nullable=True)

    # Preguntas de Seguridad (3)
    pregunta_1: Mapped[str | None] = mapped_column(db.String, nullable=True)
    pregunta_2: Mapped[str | None] = mapped_column(db.String, nullable=True)
    pregunta_3: Mapped[str | None] = mapped_column(db.String, nullable=True)
    respuesta_1: Mapped[str | None] = mapped_column(db.String, nullable=True)
    respuesta_2: Mapped[str | None] = mapped_column(db.String, nullable=True)
    respuesta_3: Mapped[str | None] = mapped_column(db.String, nullable=True)

    admin_id: Mapped[int | None] = mapped_column(
        db.Integer, db.ForeignKey("usuarios.id"), nullable=True
    )

    # Relaciones
    administrados = db.relationship(
        "Usuario", backref=db.backref("administrador", remote_side=[id])
    )
    categorias = db.relationship("Categoria", backref="creador", lazy=True)
    tipos_pago = db.relationship("TipoPago", backref="creador", lazy=True)
    cotizaciones = db.relationship("Cotizacion", backref="creador", lazy=True)
    estados = db.relationship("Estado", backref="creador", lazy=True)

    def to_dict(self) -> UsuarioDict:
        return {
            "id": self.id,
            "cedula": self.cedula,
            "nombre": self.nombre,
            "nombres": self.nombre,
            "apellidos": self.apellidos,
            "direccion": self.direccion,
            "foto_url": self.foto_url,
            "rol": self.rol,
            "activo": self.activo,
            "pregunta_1": self.pregunta_1,
            "pregunta_2": self.pregunta_2,
            "pregunta_3": self.pregunta_3,
        }

    @classmethod
    def obtener_activos_por_credenciales(
        cls, cedula: str, clave: str
    ) -> "Usuario | None":
        usuario = Usuario.query.filter_by(cedula=cedula, activo=True).first()

        if isinstance(usuario, cls):
            if check_password_hash(usuario.contrasena, clave):
                return usuario

    @classmethod
    def obtener_por_cedula(cls, cedula: str) -> "Usuario | None":
        usuario = Usuario.query.filter_by(cedula=cedula).first()

        if isinstance(usuario, cls):
            return usuario

    @classmethod
    def obtener_por_id(cls, id: int) -> "Usuario | None":
        usuario = Usuario.query.get(id)

        if isinstance(usuario, cls):
            return usuario

    @classmethod
    def instanciar_empleado(
        cls,
        cedula: str,
        nombres: str,
        clave: str,
        pregunta_1: str,
        pregunta_2: str,
        pregunta_3: str,
        respuesta_1: str,
        respuesta_2: str,
        respuesta_3: str,
    ) -> "Usuario":
        usuario = Usuario()
        usuario.cedula = cedula
        usuario.nombre = nombres
        usuario.cambiar_clave(clave)
        usuario.rol = "Empleado"
        usuario.activo = True
        usuario.pregunta_1 = pregunta_1
        usuario.pregunta_2 = pregunta_2
        usuario.pregunta_3 = pregunta_3
        usuario.respuesta_1 = respuesta_1
        usuario.respuesta_2 = respuesta_2
        usuario.respuesta_3 = respuesta_3

        return usuario

    @classmethod
    def instanciar_usuario(
        cls,
        cedula: str,
        nombres: str,
        clave: str,
        rol: Rol,
    ) -> "Usuario":
        usuario = Usuario()
        usuario.cedula = cedula
        usuario.nombre = nombres
        usuario.cambiar_clave(clave)
        usuario.rol = rol

        return usuario

    @classmethod
    def obtener_total_empleados(cls) -> int:
        return cls.query.count()

    @classmethod
    def obtener_todos_los_usuarios(cls) -> list["Usuario"]:
        return cls.query.all()

    def tiene_preguntas(self) -> bool:
        return (
            bool(self.pregunta_1)
            and bool(self.pregunta_2)
            and bool(self.pregunta_3)
        )

    def verificar_respuestas(self, respuestas: list[str]) -> bool:
        if self.respuesta_1 is None:
            self.respuesta_1 = ""

        if self.respuesta_2 is None:
            self.respuesta_2 = ""

        if self.respuesta_3 is None:
            self.respuesta_3 = ""

        return (
            len(respuestas) == 3
            and self.respuesta_1.lower().strip()
            == respuestas[0].lower().strip()
            and self.respuesta_2.lower().strip()
            == respuestas[1].lower().strip()
            and self.respuesta_3.lower().strip()
            == respuestas[2].lower().strip()
        )

    def cambiar_clave(self, clave: str) -> "Usuario":
        self.contrasena = generate_password_hash(clave)

        return self
