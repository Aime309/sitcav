from db import db


class Compra(db.Model):
    __tablename__ = "compras"

    id = db.Column(db.Integer, primary_key=True)
    id_proveedor = db.Column(
        db.Integer, db.ForeignKey("proveedores.id"), nullable=False
    )
    fecha_creacion = db.Column(db.DateTime, nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)

    # Relaciones
    detalles = db.relationship(
        "DetalleCompra",
        backref="compra",
        lazy=True,
        cascade="all, delete-orphan",
    )

    def to_dict(self):
        return {
            "id": self.id,
            "id_proveedor": self.id_proveedor,
            "proveedor": self.proveedor.to_dict() if self.proveedor else None,
            "fecha_creacion": self.fecha_creacion.strftime("%Y-%m-%d %H:%M:%S"),
            "cotizacion_dolar_bolivares": float(self.cotizacion_dolar_bolivares),
            "detalles": [d.to_dict() for d in self.detalles],
        }
