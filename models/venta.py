from db import db


class Venta(db.Model):
    __tablename__ = "ventas"

    id = db.Column(db.Integer, primary_key=True)
    id_cliente = db.Column(db.Integer, db.ForeignKey("clientes.id"), nullable=False)
    id_vendedor = db.Column(db.Integer, db.ForeignKey("usuarios.id"), nullable=True)
    fecha_creacion = db.Column(db.DateTime, nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2))

    # Relaciones
    detalles = db.relationship(
        "DetalleVenta", backref="venta", lazy=True, cascade="all, delete-orphan"
    )
    reembolsos = db.relationship("Reembolso", backref="venta", lazy=True)

    def to_dict(self):
        return {
            "id": self.id,
            "id_cliente": self.id_cliente,
            "id_vendedor": self.id_vendedor,
            "cliente": self.cliente.to_dict() if self.cliente else None,
            "fecha_creacion": self.fecha_creacion.strftime("%Y-%m-%d %H:%M:%S.%f"),
            "cotizacion_dolar_bolivares": float(self.cotizacion_dolar_bolivares)
            if self.cotizacion_dolar_bolivares
            else 0,
            "detalles": [d.to_dict_with_product() for d in self.detalles],
        }
