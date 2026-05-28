from decimal import Decimal

from flask import Blueprint, request

from models.cliente import Cliente
from models.cotizacion import Cotizacion
from db import db
from models.detalle_venta import DetalleVenta
from models.producto import Producto
from models.reembolso import Reembolso
from models.venta import Venta

ventas_bp = Blueprint("ventas", __name__, url_prefix="/ventas")


@ventas_bp.get("/")
def list_ventas():
    ventas = Venta.query.order_by(Venta.fecha_creacion.desc()).all()
    return [venta.to_dict() for venta in ventas]


@ventas_bp.post("/")
def create_venta():
    data = request.get_json()
    try:
        id_cliente = data.get("id_cliente")
        if not id_cliente:
            if "nuevo_cliente" in data:
                nuevo_cliente = Cliente(
                    nombre=data["nuevo_cliente"]["nombre"],
                    apellidos=data["nuevo_cliente"].get("apellidos", ""),
                    cedula=data["nuevo_cliente"]["cedula"],
                    telefono=data["nuevo_cliente"].get("telefono"),
                )
                db.session.add(nuevo_cliente)
                db.session.flush()
                id_cliente = nuevo_cliente.id
            else:
                return {"message": "Se requiere un cliente", "success": False}, 400

        # Obtener cotización actual
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        tasa_cambio = (
            cotizacion_actual.tasa_dolar_bolivares
            if cotizacion_actual
            else Decimal("1.00")
        )

        from datetime import datetime
        nueva_venta = Venta(
            id_cliente=id_cliente,
            id_vendedor=data.get("id_vendedor"),
            cotizacion_dolar_bolivares=tasa_cambio,
            fecha_creacion=datetime.now(),
        )
        db.session.add(nueva_venta)
        db.session.flush()

        detalles = data.get("detalles", [])
        total_venta = Decimal("0")

        for detalle_data in detalles:
            producto = db.session.get(Producto, detalle_data["id_producto"])
            if not producto:
                raise ValueError(
                    f"Producto {detalle_data['id_producto']} no encontrado"
                )

            cantidad = int(detalle_data["cantidad"])

            if producto.cantidad_disponible < cantidad:
                raise ValueError(
                    f"Stock insuficiente para {producto.nombre}. Disponible: {producto.cantidad_disponible}"
                )

            detalle = DetalleVenta(
                id_venta=nueva_venta.id,
                id_producto=producto.id,
                precio_unitario_tipo_dolares=producto.precio_unitario_actual_dolares,
                cantidad=cantidad,
                esta_apartado=detalle_data.get("esta_apartado", False),
            )
            db.session.add(detalle)

            producto.cantidad_disponible -= cantidad
            total_venta += producto.precio_unitario_actual_dolares * cantidad

        db.session.commit()

        return {
            "success": True,
            "message": "Venta creada exitosamente",
            "venta": nueva_venta.to_dict(),
            "total": float(total_venta),
            "tasa_cambio": float(tasa_cambio),
        }, 201

    except ValueError as ve:
        db.session.rollback()
        return {"message": str(ve), "success": False}, 400
    except Exception as e:
        import traceback
        db.session.rollback()
        return {"message": f"Error al crear venta: {str(e)}", "success": False}, 500


@ventas_bp.get("/<int:id>")
def get_venta(id: int):
    venta = db.session.get(Venta, id)
    if not venta:
        return {"message": "Venta no encontrada", "success": False}, 404
    return venta.to_dict()


@ventas_bp.delete("/<int:id>")
def delete_venta(id: int):
    try:
        venta = db.session.get(Venta, id)
        if not venta:
            return {"message": "Venta no encontrada", "success": False}, 404

        # Check for associated refunds
        reembolsos = Reembolso.query.filter_by(id_venta=id).count()
        if reembolsos > 0:
            return {
                "success": False,
                "message": f"Esta venta tiene {reembolsos} reembolso(s) asociado(s). Debe eliminar los reembolsos primero antes de eliminar la venta.",
                "has_refunds": True,
            }, 400

        # Restore stock for each product in the sale
        for detalle in venta.detalles:
            producto = db.session.get(Producto, detalle.id_producto)
            if producto:
                producto.cantidad_disponible += detalle.cantidad

        db.session.delete(venta)
        db.session.commit()

        return {"success": True, "message": "Venta eliminada y stock restaurado"}
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al eliminar venta: {str(e)}", "success": False}, 500
