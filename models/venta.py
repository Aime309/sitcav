from datetime import datetime
from typing import Any, TypedDict

from sqlalchemy.orm import Mapped, mapped_column

from db import db


class VentaDict(TypedDict):
    id: int
    id_cliente: int
    id_vendedor: int | None
    cliente: Any
    fecha_creacion: Any
    cotizacion_dolar_bolivares: float
    detalles: Any


class Venta(db.Model):
    __tablename__: str = "ventas"

    id: Mapped[int] = mapped_column(db.Integer, primary_key=True)
    id_cliente: Mapped[int] = mapped_column(
        db.Integer, db.ForeignKey("clientes.id"), nullable=False
    )
    id_vendedor: Mapped[int | None] = mapped_column(
        db.Integer, db.ForeignKey("usuarios.id"), nullable=True
    )
    fecha_creacion = mapped_column(db.DateTime, nullable=False)
    cotizacion_dolar_bolivares = mapped_column(db.Numeric(10, 2))

    # Relaciones
    detalles = db.relationship(
        "DetalleVenta", backref="venta", lazy=True, cascade="all, delete-orphan"
    )
    reembolsos = db.relationship("Reembolso", backref="venta", lazy=True)

    def to_dict(self) -> VentaDict:
        return {
            "id": self.id,
            "id_cliente": self.id_cliente,
            "id_vendedor": self.id_vendedor,
            "cliente": self.cliente.to_dict() if self.cliente else None,
            "fecha_creacion": self.fecha_creacion.strftime(
                "%Y-%m-%d %H:%M:%S.%f"
            ),
            "cotizacion_dolar_bolivares": float(self.cotizacion_dolar_bolivares)
            if self.cotizacion_dolar_bolivares
            else 0,
            "detalles": [detalle.to_dict_with_product() for detalle in self.detalles],
        }

    @classmethod
    def obtener_cantidad_ventas_mes(cls) -> int:
        hoy = datetime.now()
        inicio_mes = datetime(hoy.year, hoy.month, 1)

        return Venta.query.filter(Venta.fecha_creacion >= inicio_mes).count()
