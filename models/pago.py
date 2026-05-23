from db import db


class Pago(db.Model):
    __tablename__ = "pagos"

    id = db.Column(db.Integer, primary_key=True)
    id_tipo_pago = db.Column(db.Integer, db.ForeignKey("tipos_pago.id"), nullable=False)
    id_detalle_venta = db.Column(
        db.Integer, db.ForeignKey("detalles_ventas.id"), nullable=False
    )
    fecha_creacion = db.Column(db.DateTime, server_default=db.func.current_timestamp(), nullable=False)
    cotizacion_dolar_bolivares = db.Column(db.Numeric(10, 2), nullable=False)
    monto = db.Column(
        db.Numeric(10, 2), nullable=False
    )  # En dólares o bolívares según config

    def to_dict(self):
        return {
            "id": self.id,
            "id_tipo_pago": self.id_tipo_pago,
            "id_detalle_venta": self.id_detalle_venta,
            "fecha_creacion": self.fecha_creacion.strftime("%Y-%m-%d %H:%M:%S"),
            "monto": float(self.monto),
            "cotizacion_dolar_bolivares": float(self.cotizacion_dolar_bolivares),
        }
