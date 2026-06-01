from typing import Any, TypedDict

from sqlalchemy.orm import Mapped, mapped_column

from db import db


class CotizacionDict(TypedDict):
    id: int
    fecha_hora: Any
    tasa_dolar_bolivares: Any


class Cotizacion(db.Model):
    __tablename__: str = "cotizaciones"

    id: Mapped[int] = mapped_column(db.Integer, primary_key=True)
    id_usuario: Mapped[int] = mapped_column(
        db.Integer, db.ForeignKey("usuarios.id"), nullable=False
    )
    fecha_hora = mapped_column(db.DateTime, nullable=False)
    tasa_dolar_bolivares = mapped_column(db.Numeric(10, 2), nullable=False)

    def to_dict(self) -> CotizacionDict:
        return {
            "id": self.id,
            "fecha_hora": self.fecha_hora.strftime("%Y-%m-%d %H:%M:%S"),
            "tasa_dolar_bolivares": float(self.tasa_dolar_bolivares),
        }

    @classmethod
    def obtener_tasa_actual(cls) -> float:
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()

        return (
            float(cotizacion_actual.tasa_dolar_bolivares)
            if cotizacion_actual
            else 0.0
        )
