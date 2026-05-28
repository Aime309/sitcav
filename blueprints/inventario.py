from flask import Blueprint, request

from db import db
from models.movimiento_inventario import MovimientoInventario
from models.producto import Producto

inventario_bp = Blueprint("inventario", __name__, url_prefix="/inventario")


@inventario_bp.get("/")
def select_inventory():
    productos = Producto.query.all()
    resultado = []

    for prod in productos:
        # Calcular cantidad apartada
        cantidad_apartada = 0
        for detalle in prod.detalles_apartados:
            if detalle.apartado.estado == "activo":
                cantidad_apartada += detalle.cantidad

        prod_dict = prod.to_dict()
        prod_dict["cantidad_apartada"] = cantidad_apartada
        prod_dict["cantidad_total"] = prod.cantidad_disponible + cantidad_apartada
        if prod.categoria:
            prod_dict["categoria_nombre"] = prod.categoria.nombre
        resultado.append(prod_dict)

    return resultado


@inventario_bp.get("/movimientos")
def select_inventory_movements():
    id_producto = request.args.get("id_producto", None)
    tipo = request.args.get("tipo", None)

    query = MovimientoInventario.query

    if id_producto:
        query = query.filter_by(id_producto=id_producto)
    if tipo:
        query = query.filter_by(tipo=tipo)

    movimientos = query.order_by(MovimientoInventario.fecha.desc()).limit(100).all()
    return [m.to_dict() for m in movimientos]


@inventario_bp.post("/ajuste")
def update_inventory():
    data = request.get_json()

    id_producto = data.get("id_producto")
    cantidad = int(data.get("cantidad", 0))
    tipo = data.get("tipo")  # entrada o salida
    observacion = data.get("observacion", "Ajuste manual")

    if not id_producto or not tipo or cantidad <= 0:
        return {"success": False, "message": "Datos incompletos"}, 400

    producto = Producto.query.get(id_producto)
    if not producto:
        return {"success": False, "message": "Producto no encontrado"}, 404

    try:
        if tipo == "entrada":
            producto.cantidad_disponible += cantidad
        elif tipo == "salida":
            if producto.cantidad_disponible < cantidad:
                return {"success": False, "message": "Stock insuficiente"}, 400
            producto.cantidad_disponible -= cantidad
        else:
            return {
                "success": False,
                "message": "Tipo debe ser 'entrada' o 'salida'",
            }, 400

        # Registrar movimiento
        movimiento = MovimientoInventario(
            id_producto=producto.id,
            tipo=tipo,
            cantidad=cantidad,
            motivo="ajuste_manual",
            observacion=observacion,
        )
        db.session.add(movimiento)
        db.session.commit()

        return {
            "success": True,
            "message": "Ajuste realizado exitosamente",
            "producto": producto.to_dict(),
            "movimiento": movimiento.to_dict(),
        }

    except Exception as e:
        db.session.rollback()
        return {"success": False, "message": f"Error: {str(e)}"}, 500
