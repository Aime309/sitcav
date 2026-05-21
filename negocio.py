from flask import Blueprint, request

from models import Negocio, db

negocio_bp = Blueprint("negocio", __name__, url_prefix="/negocio")


@negocio_bp.get("/")
def get_negocio():
    negocio = Negocio.query.first()
    if negocio:
        return {
            "id": negocio.id,
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
            "direccion": negocio.direccion,
        }
    return {"message": "No hay datos del negocio"}, 404


@negocio_bp.put("/")
def update_negocio():
    negocio = Negocio.query.first()
    data = request.get_json()

    try:
        if negocio:
            negocio.nombre = data.get("nombre", negocio.nombre)
            negocio.rif = data.get("rif", negocio.rif)
            negocio.telefono = data.get("telefono", negocio.telefono)
            negocio.direccion = data.get("direccion", negocio.direccion)
        else:
            negocio = Negocio(
                nombre=data["nombre"],
                rif=data.get("rif"),
                telefono=data.get("telefono"),
                direccion=data.get("direccion"),
                id_localidad=data.get("id_localidad", 1),
                id_sector=data.get("id_sector", 1),
            )
            db.session.add(negocio)

        db.session.commit()
        return {"success": True, "message": "Datos actualizados"}
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error: {str(e)}", "success": False}, 400
