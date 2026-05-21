from flask import Blueprint, jsonify

from models import Localidad

localidades_bp = Blueprint("localidades", __name__, url_prefix="/localidades")


@localidades_bp.get("/<int:estado_id>")
def list_localidades(estado_id: int):
    localidades = Localidad.query.filter_by(id_estado=estado_id).all()
    return jsonify([{"id": l.id, "nombre": l.nombre} for l in localidades])
