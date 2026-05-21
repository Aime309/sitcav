from flask import Blueprint, request

from models import Proveedor, db

proveedores_bp = Blueprint("proveedores", __name__, url_prefix="/proveedores")


@proveedores_bp.get("/")
def list_proveedores():
    proveedores = Proveedor.query.all()
    return [prov.to_dict() for prov in proveedores]


@proveedores_bp.post("/")
def create_proveedor():
    data = request.get_json()
    try:
        nuevo_proveedor = Proveedor(
            nombre=data["nombre"],
            rif=data.get("rif"),
            telefono=data.get("telefono"),
            direccion=data.get("direccion"),
            id_estado=data.get("id_estado"),
            id_localidad=data.get("id_localidad"),
            id_sector=data.get("id_sector"),
        )
        db.session.add(nuevo_proveedor)
        db.session.commit()
        return nuevo_proveedor.to_dict(), 201
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al crear proveedor: {str(e)}", "success": False}, 400


@proveedores_bp.put("/<int:id>")
def update_proveedor(id: int):
    proveedor = Proveedor.query.get_or_404(id)
    data = request.get_json()
    try:
        proveedor.nombre = data.get("nombre", proveedor.nombre)
        proveedor.rif = data.get("rif", proveedor.rif)
        proveedor.telefono = data.get("telefono", proveedor.telefono)
        proveedor.direccion = data.get("direccion", proveedor.direccion)

        db.session.commit()
        return proveedor.to_dict()
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al actualizar proveedor: {str(e)}",
            "success": False,
        }, 400


@proveedores_bp.delete("/<int:id>")
def delete_proveedor(id: int):
    proveedor = Proveedor.query.get(id)
    if proveedor is None:
        return {"message": "Proveedor no encontrado"}, 404

    try:
        db.session.delete(proveedor)
        db.session.commit()
        return {"message": "Proveedor eliminado con éxito", "success": True}
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar proveedor: {str(e)}",
            "success": False,
        }, 500
