from flask import Blueprint, jsonify

from models import Usuario, db

empleados_bp = Blueprint("empleados", __name__, url_prefix="/empleados")


@empleados_bp.get("/")
def list_empleados():
    try:
        empleados = Usuario.query.all()
        return jsonify(
            [
                {
                    "id": u.id,
                    "nombre": u.nombre,
                    "cedula": u.cedula,
                    "rol": u.rol,
                }
                for u in empleados
            ]
        )
    except Exception:
        return jsonify([])


@empleados_bp.delete("/<int:id>")
def delete_empleado(id: int):
    empleado = Usuario.query.get(id)
    if empleado is None:
        return jsonify({"message": "Empleado no encontrado"}), 404

    try:
        db.session.delete(empleado)
        db.session.commit()
        return jsonify({"message": "Empleado eliminado con éxito", "success": True})
    except Exception as e:
        db.session.rollback()
        return jsonify(
            {
                "message": f"Error al eliminar empleado: {str(e)}",
                "success": False,
            }
        ), 500
