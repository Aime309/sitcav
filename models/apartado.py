from db import db


class Apartado(db.Model):
    __tablename__ = "apartados"

    id = db.Column(db.Integer, primary_key=True)
    id_cliente = db.Column(db.Integer, db.ForeignKey("clientes.id"), nullable=False)
    fecha_creacion = db.Column(db.DateTime, nullable=False)
    fecha_limite = db.Column(db.DateTime, nullable=False)  # Generalmente 3 meses
    monto_total = db.Column(db.Numeric(10, 2), nullable=False)
    monto_pagado = db.Column(db.Numeric(10, 2))
    estado = db.Column(db.String(20))  # activo, completado, cancelado
    observaciones = db.Column(db.Text, nullable=True)

    # Relaciones
    cliente = db.relationship("Cliente", backref=db.backref("apartados", lazy=True))
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

    def to_dict(self):
        return {
            "id": self.id,
            "id_cliente": self.id_cliente,
            "cliente": self.cliente.to_dict() if self.cliente else None,
            "fecha_creacion": self.fecha_creacion.strftime("%Y-%m-%d %H:%M:%S"),
            "fecha_limite": self.fecha_limite.strftime("%Y-%m-%d %H:%M:%S"),
            "monto_total": float(self.monto_total),
            "monto_pagado": float(self.monto_pagado),
            "monto_pendiente": float(self.monto_total) - float(self.monto_pagado),
            "estado": self.estado,
            "observaciones": self.observaciones,
            "detalles": [d.to_dict() for d in self.detalles],
            "pagos": [p.to_dict() for p in self.pagos],
        }
