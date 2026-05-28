from flask import Blueprint

from db import db
from models.usuario import Usuario

empleados_bp = Blueprint("empleados", __name__, url_prefix="/empleados")


@empleados_bp.get("/")
def select_employees():
    try:
        empleados = Usuario.query.all()
        return [
            {
                "id": u.id,
                "nombre": u.nombre,
                "cedula": u.cedula,
                "rol": u.rol,
            }
            for u in empleados
        ]
    except Exception:
        return []


@empleados_bp.delete("/<int:id>")
def delete_employee(id: int):
    empleado = Usuario.query.get(id)
    if empleado is None:
        return {"message": "Empleado no encontrado"}, 404

    try:
        db.session.delete(empleado)
        db.session.commit()
        return {"message": "Empleado eliminado con éxito", "success": True}
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar empleado: {str(e)}",
            "success": False,
        }, 500
