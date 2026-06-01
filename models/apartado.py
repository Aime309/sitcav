from typing import Any, Literal, TypedDict

from sqlalchemy.orm import Mapped, mapped_column

from db import db

Estado = Literal["activo"] | Literal["completado"] | Literal["cancelado"]


class ApartadoDict(TypedDict):
    id: int
    id_cliente: int
    cliente: Any
    fecha_creacion: Any
    fecha_limite: Any
    monto_total: float
    monto_pagado: float
    monto_pendiente: float
    estado: Any
    observaciones: Any
    detalles: Any
    pagos: Any


class Apartado(db.Model):
    __tablename__: str = "apartados"

    id: Mapped[int] = mapped_column(db.Integer, primary_key=True)
    id_cliente: Mapped[int] = mapped_column(
        db.Integer, db.ForeignKey("clientes.id"), nullable=False
    )
    fecha_creacion = mapped_column(db.DateTime, nullable=False)
    fecha_limite = mapped_column(db.DateTime, nullable=False)
    monto_total = mapped_column(db.Numeric(10, 2), nullable=False)
    monto_pagado = mapped_column(db.Numeric(10, 2))
    estado = mapped_column(db.String)
    observaciones = mapped_column(db.Text, nullable=True)

    # Relaciones
    cliente = db.relationship(
        "Cliente", backref=db.backref("apartados", lazy=True)
    )
    detalles = db.relationship(
        "DetalleApartado",
        backref="apartado",
        lazy=True,
        cascade="all, delete-orphan",
    )
    pagos = db.relationship(
        "PagoApartado",
        backref="apartado",
        lazy=True,
        cascade="all, delete-orphan",
    )

    def to_dict(self) -> ApartadoDict:
        return {
            "id": self.id,
            "id_cliente": self.id_cliente,
            "cliente": self.cliente.to_dict() if self.cliente else None,
            "fecha_creacion": self.fecha_creacion.strftime("%Y-%m-%d %H:%M:%S"),
            "fecha_limite": self.fecha_limite.strftime("%Y-%m-%d %H:%M:%S"),
            "monto_total": float(self.monto_total),
            "monto_pagado": float(self.monto_pagado),
            "monto_pendiente": float(self.monto_total)
            - float(self.monto_pagado),
            "estado": self.estado,
            "observaciones": self.observaciones,
            "detalles": [detalle.to_dict() for detalle in self.detalles],
            "pagos": [pago.to_dict() for pago in self.pagos],
        }

    @classmethod
    def obtener_cantidad_apartados_activos(cls) -> int:
        return Apartado.query.filter_by(estado="activo").count()
