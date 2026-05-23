from flask import Blueprint

from tipo_pago import TipoPago


tipos_pago_bp = Blueprint("tipos_pago", __name__, url_prefix="/tipos-pago")


@tipos_pago_bp.get("/")
def list_tipos_pago():
    tipos = TipoPago.query.all()
    return [tipo.to_dict() for tipo in tipos]
