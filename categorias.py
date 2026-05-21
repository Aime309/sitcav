from flask import Blueprint, jsonify, request

from models import Categoria, Producto, db

categorias_bp = Blueprint("categorias", __name__, url_prefix="/categorias")


@categorias_bp.get("/")
def list_categorias():
    categorias = Categoria.query.all()
    return jsonify([cat.to_dict() for cat in categorias])


@categorias_bp.post("/")
def create_categoria():
    data = request.get_json()
    try:
        nueva_categoria = Categoria(
            nombre=data["nombre"], id_usuario=data.get("id_usuario", 1)
        )
        db.session.add(nueva_categoria)
        db.session.commit()
        return jsonify(nueva_categoria.to_dict()), 201
    except Exception as e:
        db.session.rollback()
        return jsonify(
            {"message": f"Error al crear categoría: {str(e)}", "success": False}
        ), 400


@categorias_bp.delete("/<int:id>")
def delete_categoria(id: int):
    try:
        categoria = Categoria.query.get_or_404(id)

        # Verificar si hay productos asociados a esta categoría
        productos_asociados = Producto.query.filter_by(id_categoria=id).count()
        if productos_asociados > 0:
            return jsonify(
                {
                    "message": f"No se puede eliminar la categoría '{categoria.nombre}' porque tiene {productos_asociados} producto(s) asociado(s). Reasigne o elimine los productos primero.",
                    "success": False,
                }
            ), 400

        db.session.delete(categoria)
        db.session.commit()
        return jsonify(
            {"message": "Categoría eliminada correctamente", "success": True}
        )
    except Exception as e:
        db.session.rollback()
        return jsonify(
            {
                "message": f"Error al eliminar categoría: {str(e)}",
                "success": False,
            }
        ), 400
