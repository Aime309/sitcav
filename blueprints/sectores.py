from flask import Blueprint

from sector import Sector

sectores_bp = Blueprint("sectores", __name__, url_prefix="/sectores")


@sectores_bp.get("/<int:localidad_id>")
def list_sectores(localidad_id: int):
    sectores = Sector.query.filter_by(id_localidad=localidad_id).all()
    return [{"id": s.id, "nombre": s.nombre} for s in sectores]
