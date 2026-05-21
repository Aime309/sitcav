from decimal import Decimal

from flask import Blueprint, request, send_file

from compra import Compra
from cotizacion_model import Cotizacion
from db import db
from detalle_compra import DetalleCompra
from negocio_model import Negocio
from producto import Producto
from proveedor import Proveedor

compras_bp = Blueprint("compras", __name__, url_prefix="/compras")


@compras_bp.get("/")
def list_compras():
    compras = Compra.query.order_by(Compra.fecha_creacion.desc()).all()
    return [compra.to_dict() for compra in compras]


@compras_bp.post("/")
def create_compra():
    data = request.get_json()
    try:
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        if not cotizacion_actual:
            return {"message": "No hay cotización registrada", "success": False}, 400

        nueva_compra = Compra(
            id_proveedor=data["id_proveedor"],
            cotizacion_dolar_bolivares=cotizacion_actual.tasa_dolar_bolivares,
        )
        db.session.add(nueva_compra)
        db.session.flush()

        detalles = data.get("detalles", [])

        for detalle_data in detalles:
            producto = db.session.get(Producto, detalle_data["id_producto"])
            if not producto:
                raise ValueError(
                    f"Producto {detalle_data['id_producto']} no encontrado"
                )

            cantidad = int(detalle_data["cantidad"])
            precio = Decimal(str(detalle_data["precio_unitario"]))

            detalle = DetalleCompra(
                id_compra=nueva_compra.id,
                id_producto=producto.id,
                precio_unitario_tipo_dolares=precio,
                cantidad=cantidad,
            )
            db.session.add(detalle)

            producto.cantidad_disponible += cantidad

        db.session.commit()

        return {
            "success": True,
            "message": "Compra registrada exitosamente",
            "compra": nueva_compra.to_dict(),
        }, 201

    except ValueError as ve:
        db.session.rollback()
        return {"message": str(ve), "success": False}, 400
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al crear compra: {str(e)}", "success": False}, 500


@compras_bp.delete("/<int:id>")
def delete_compra(id: int):
    try:
        compra = Compra.query.get_or_404(id)

        # Revertir stock
        for detalle in compra.detalles:
            producto = Producto.query.get(detalle.id_producto)
            if producto:
                producto.cantidad_disponible -= detalle.cantidad

        db.session.delete(compra)
        db.session.commit()

        return {"success": True, "message": "Compra eliminada y stock revertido"}
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al eliminar compra: {str(e)}", "success": False}, 500


@compras_bp.get("/<int:id>/pdf")
def get_compra_pdf(id: int):
    try:
        compra = Compra.query.get_or_404(id)
        negocio = Negocio.query.first()

        if not negocio:
            return {"message": "Datos del negocio no configurados"}, 400

        negocio_data = {
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
            "direccion": negocio.direccion,
        }

        # Enriquecer datos de compra con proveedor
        compra_data = compra.to_dict()
        proveedor = Proveedor.query.get(compra.id_proveedor)
        if proveedor:
            compra_data["proveedor"] = proveedor.to_dict()

        # Enriquecer detalles con nombre de producto
        for detalle in compra_data["detalles"]:
            prod = Producto.query.get(detalle["id_producto"])
            if prod:
                detalle["producto"] = {"nombre": prod.nombre}

        from pdf_generator import generar_factura_compra_pdf

        pdf_path = generar_factura_compra_pdf(compra_data, negocio_data)

        return send_file(pdf_path, as_attachment=True)

    except Exception as e:
        return {"message": f"Error al generar PDF: {str(e)}", "success": False}, 500


@compras_bp.get("/<int:id>")
def get_compra(id: int):
    compra = Compra.query.get_or_404(id)
    return compra.to_dict()
