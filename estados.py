from flask import Blueprint, jsonify

from models import Estado

estados_bp = Blueprint("estados", __name__, url_prefix="/estados")


@estados_bp.get("/")
def list_estados():
    estados = Estado.query.all()
    return jsonify([{"id": e.id, "nombre": e.nombre} for e in estados])
