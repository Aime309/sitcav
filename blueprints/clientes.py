from flask import Blueprint, request

from models.cliente import Cliente
from db import db

clientes_bp = Blueprint("clientes", __name__, url_prefix="/clientes")


@clientes_bp.get("/")
def list_clientes():
    clientes = Cliente.query.all()
    return [cliente.to_dict() for cliente in clientes]


@clientes_bp.post("/")
def create_cliente():
    data = request.get_json()
    if not data:
        return {"message": "No se recibieron datos", "success": False}, 400

    # Validar campos requeridos
    if not data.get("nombre") or not data.get("cedula"):
        return {"message": "Nombre y cédula son requeridos", "success": False}, 400

    try:
        nuevo_cliente = Cliente(
            nombre=data["nombre"],
            apellidos=data.get("apellidos", ""),
            cedula=data["cedula"],
            telefono=data.get("telefono"),
            direccion=data.get("direccion"),
            id_localidad=data.get("id_localidad"),
        )
        db.session.add(nuevo_cliente)
        db.session.commit()
        return nuevo_cliente.to_dict(), 201
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al crear cliente: {str(e)}", "success": False}, 400


@clientes_bp.put("/<int:id>")
def update_cliente(id: int):
    cliente = Cliente.query.get_or_404(id)
    data = request.get_json()
    try:
        cliente.nombre = data.get("nombre", cliente.nombre)
        cliente.apellidos = data.get("apellidos", cliente.apellidos)
        cliente.cedula = data.get("cedula", cliente.cedula)
        cliente.telefono = data.get("telefono", cliente.telefono)
        cliente.direccion = data.get("direccion", cliente.direccion)
        cliente.id_localidad = data.get("id_localidad", cliente.id_localidad)

        db.session.commit()
        return cliente.to_dict()
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al actualizar cliente: {str(e)}",
            "success": False,
        }, 400


@clientes_bp.delete("/<int:id>")
def delete_cliente(id: int):
    cliente = Cliente.query.get(id)
    if cliente is None:
        return {"message": "Cliente no encontrado"}, 404

    try:
        db.session.delete(cliente)
        db.session.commit()
        return {"message": "Cliente eliminado con éxito", "success": True}
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar cliente: {str(e)}",
            "success": False,
        }, 500


@clientes_bp.get("/buscar")
def search_clientes():
    query = request.args.get("q", "")
    clientes = Cliente.query.filter(
        (Cliente.nombre.ilike(f"%{query}%"))
        | (Cliente.apellidos.ilike(f"%{query}%"))
        | (Cliente.cedula.ilike(f"%{query}%"))
    ).all()
    return [c.to_dict() for c in clientes]
