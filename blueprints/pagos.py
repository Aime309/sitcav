from flask import Blueprint, request

from models.cotizacion import Cotizacion
from db import db
from models.pago import Pago
from models.venta import Venta

pagos_bp = Blueprint("pagos", __name__, url_prefix="/pagos")


@pagos_bp.get("/venta/<int:venta_id>")
def select_sale_payments(venta_id: int):
    venta = Venta.query.get_or_404(venta_id)
    pagos = []
    for detalle in venta.detalles:
        for pago in detalle.pagos:
            pagos.append(pago.to_dict())
    return pagos


@pagos_bp.post("/")
def select_payment():
    data = request.get_json()
    try:
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        if not cotizacion_actual:
            return {"message": "No hay cotización registrada", "success": False}, 400

        nuevo_pago = Pago(
            id_tipo_pago=data["id_tipo_pago"],
            id_detalle_venta=data["id_detalle_venta"],
            monto=Decimal(str(data["monto"])),
            cotizacion_dolar_bolivares=cotizacion_actual.tasa_dolar_bolivares,
        )
        db.session.add(nuevo_pago)
        db.session.commit()

        return nuevo_pago.to_dict(), 201
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al registrar pago: {str(e)}", "success": False}, 400
