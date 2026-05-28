import os
from datetime import datetime
from decimal import Decimal

from flask import Blueprint, current_app, request
from models.producto import Producto
from sqlalchemy.exc import IntegrityError
from werkzeug.utils import secure_filename

from db import db

productos_bp = Blueprint("productos", __name__, url_prefix="/productos")


@productos_bp.get("/")
def list_productos():
    productos = Producto.query.all()
    resultado = []
    for prod in productos:
        prod_dict = prod.to_dict()
        # Agregar nombre de categoría
        if prod.categoria:
            prod_dict["categoria_nombre"] = prod.categoria.nombre
        resultado.append(prod_dict)
    return resultado


@productos_bp.post("/")
def create_product():
    try:
        # Manejar multipart/form-data o JSON
        if request.content_type and "multipart/form-data" in request.content_type:
            data = request.form
            file = request.files.get("imagen_file")
        else:
            data = request.get_json()
            file = None

        imagen_url = data.get("imagen_url")

        # Procesar archivo si existe
        if file and file.filename:
            filename = secure_filename(
                f"{int(datetime.now().timestamp())}_{file.filename}"
            )
            save_path = os.path.join(
                current_app.config["PRODUCTS_UPLOAD_FOLDER"], filename
            )
            file.save(save_path)
            imagen_url = f"/uploads/{filename}"

        nuevo_producto = Producto(
            nombre=data["nombre"],
            codigo=data["codigo"],
            descripcion=data.get("descripcion", ""),
            id_categoria=data["id_categoria"],
            id_proveedor=data.get("id_proveedor"),
            precio_unitario_actual_dolares=data["precio_unitario_actual_dolares"],
            cantidad_disponible=data.get("cantidad_disponible", 0),
            dias_garantia=data.get("dias_garantia", 0),
            dias_apartado=data.get("dias_apartado", 0),
            imagen_url=imagen_url,
            imei=data.get("imei"),
        )
        db.session.add(nuevo_producto)
        db.session.commit()
        return {"success": True, "producto": nuevo_producto.to_dict()}, 201
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al crear producto: {str(e)}", "success": False}, 400


@productos_bp.put("/<int:id>")
def update_producto(id: int):
    producto = Producto.query.get_or_404(id)

    try:
        # Manejar multipart/form-data o JSON
        if request.content_type and "multipart/form-data" in request.content_type:
            data = request.form
            file = request.files.get("imagen_file")
        else:
            data = request.get_json()
            file = None

        producto.nombre = data.get("nombre", producto.nombre)
        producto.descripcion = data.get("descripcion", producto.descripcion)
        producto.codigo = data.get("codigo", producto.codigo)
        producto.id_categoria = data.get("id_categoria", producto.id_categoria)
        producto.precio_unitario_actual_dolares = Decimal(
            str(
                data.get(
                    "precio_unitario_actual_dolares",
                    producto.precio_unitario_actual_dolares,
                )
            )
        )
        producto.cantidad_disponible = data.get(
            "cantidad_disponible", producto.cantidad_disponible
        )
        producto.imei = data.get("imei", producto.imei)

        # Actualizar imagen si se envía una nueva URL o archivo
        if file and file.filename:
            filename = secure_filename(
                f"{int(datetime.now().timestamp())}_{file.filename}"
            )
            save_path = os.path.join(
                current_app.config["PRODUCTS_UPLOAD_FOLDER"], filename
            )
            file.save(save_path)
            producto.imagen_url = f"/uploads/{filename}"
        elif "imagen_url" in data:
            producto.imagen_url = data.get("imagen_url")

        db.session.commit()
        return producto.to_dict()
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al actualizar producto: {str(e)}",
            "success": False,
        }, 400


@productos_bp.delete("/<int:id>")
def delete_producto(id: int):
    producto = Producto.query.get(id)
    if producto is None:
        return {"message": "Producto no encontrado"}, 404

    try:
        db.session.delete(producto)
        db.session.commit()
        return {"message": "Producto eliminado con éxito", "success": True}
    except IntegrityError:
        db.session.rollback()
        return {
            "message": "No se puede eliminar el producto porque tiene historial (ventas, apartados o movimientos). Considere desactivarlo o dejar el stock en 0.",
            "success": False,
        }, 400
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar producto: {str(e)}",
            "success": False,
        }, 500


@productos_bp.get("/buscar")
def search_productos():
    query = request.args.get("q", "")
    productos = Producto.query.filter(
        (Producto.nombre.ilike(f"%{query}%")) | (Producto.codigo.ilike(f"%{query}%"))
    ).all()
    return [p.to_dict() for p in productos]


@productos_bp.get("/stock-bajo")
def productos_stock_bajo():
    productos = Producto.query.filter(Producto.cantidad_disponible < 10).all()
    return [p.to_dict() for p in productos]
