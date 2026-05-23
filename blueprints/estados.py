from flask import Blueprint

from estado import Estado

estados_bp = Blueprint("estados", __name__, url_prefix="/estados")


@estados_bp.get("/")
def list_estados():
    estados = Estado.query.all()
    return [{"id": e.id, "nombre": e.nombre} for e in estados]
