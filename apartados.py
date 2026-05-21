import os
from datetime import datetime, timedelta
from decimal import Decimal

from flask import Blueprint, jsonify, request, send_file

from models import (
    Apartado,
    Cotizacion,
    DetalleApartado,
    DetalleVenta,
    MovimientoInventario,
    Negocio,
    PagoApartado,
    Producto,
    Venta,
    db,
)

apartados_bp = Blueprint("apartados", __name__, url_prefix="/apartados")


@apartados_bp.get("/")
def list_apartados():
    estado = request.args.get("estado", None)
    query = Apartado.query
    if estado:
        query = query.filter_by(estado=estado)
    apartados = query.order_by(Apartado.fecha_creacion.desc()).all()
    return jsonify([a.to_dict() for a in apartados])


@apartados_bp.post("/")
def create_apartado():
    data = request.get_json()
    try:
        id_cliente = data.get("id_cliente")
        if not id_cliente:
            return jsonify({"success": False, "message": "Se requiere un cliente"}), 400

        productos = data.get("productos", [])
        if not productos:
            return jsonify(
                {
                    "success": False,
                    "message": "Se requiere al menos un producto",
                }
            ), 400

        # Calcular monto total y validar stock
        monto_total = Decimal("0")
        for item in productos:
            producto = Producto.query.get(item["id_producto"])
            if not producto:
                return jsonify(
                    {
                        "success": False,
                        "message": f"Producto {item['id_producto']} no encontrado",
                    }
                ), 404
            if producto.cantidad_disponible < item["cantidad"]:
                return jsonify(
                    {
                        "success": False,
                        "message": f"Stock insuficiente para {producto.nombre}",
                    }
                ), 400
            monto_total += (
                Decimal(str(producto.precio_unitario_actual_dolares)) * item["cantidad"]
            )

        # Calcular fecha límite (3 meses por defecto)
        dias_limite = data.get("dias_limite", 90)
        fecha_limite = datetime.now() + timedelta(days=dias_limite)

        # Crear apartado
        nuevo_apartado = Apartado(
            id_cliente=id_cliente,
            fecha_limite=fecha_limite,
            monto_total=monto_total,
            monto_pagado=Decimal("0"),
            estado="activo",
            observaciones=data.get("observaciones", ""),
        )
        db.session.add(nuevo_apartado)
        db.session.flush()

        # Crear detalles y reducir stock
        for item in productos:
            producto = Producto.query.get(item["id_producto"])

            detalle = DetalleApartado(
                id_apartado=nuevo_apartado.id,
                id_producto=producto.id,
                cantidad=item["cantidad"],
                precio_unitario=producto.precio_unitario_actual_dolares,
            )
            db.session.add(detalle)

            # Reducir stock
            producto.cantidad_disponible -= item["cantidad"]

            # Registrar movimiento de inventario
            movimiento = MovimientoInventario(
                id_producto=producto.id,
                tipo="salida",
                cantidad=item["cantidad"],
                motivo="apartado",
                referencia_id=nuevo_apartado.id,
                referencia_tipo="apartado",
                observacion=f"Apartado #{nuevo_apartado.id} creado",
            )
            db.session.add(movimiento)

        # Registrar pago inicial si se proporciona
        abono_inicial = data.get("abono_inicial", 0)
        if abono_inicial and float(abono_inicial) > 0:
            pago = PagoApartado(
                id_apartado=nuevo_apartado.id,
                monto=Decimal(str(abono_inicial)),
                observacion="Abono inicial",
            )
            db.session.add(pago)
            nuevo_apartado.monto_pagado = Decimal(str(abono_inicial))

        db.session.commit()

        return jsonify(
            {
                "success": True,
                "message": "Apartado creado exitosamente",
                "apartado": nuevo_apartado.to_dict(),
            }
        ), 201

    except Exception as e:
        db.session.rollback()
        return jsonify(
            {"success": False, "message": f"Error al crear apartado: {str(e)}"}
        ), 500


@apartados_bp.get("/<int:id>")
def get_apartado(id: int):
    apartado = Apartado.query.get_or_404(id)
    return jsonify(apartado.to_dict())


@apartados_bp.post("/<int:id>/pago")
def registrar_pago_apartado(id: int):
    apartado = Apartado.query.get_or_404(id)

    if apartado.estado != "activo":
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400

    data = request.get_json()
    monto = Decimal(str(data.get("monto", 0)))

    if monto <= 0:
        return jsonify(
            {"success": False, "message": "El monto debe ser mayor a 0"}
        ), 400

    try:
        # Registrar pago
        pago = PagoApartado(
            id_apartado=apartado.id,
            monto=monto,
            observacion=data.get("observacion", ""),
        )
        db.session.add(pago)

        # Actualizar monto pagado
        apartado.monto_pagado += monto

        # Verificar si está completamente pagado
        if apartado.monto_pagado >= apartado.monto_total:
            apartado.estado = "completado"

        db.session.commit()

        return jsonify(
            {
                "success": True,
                "message": "Pago registrado exitosamente",
                "apartado": apartado.to_dict(),
            }
        )

    except Exception as e:
        db.session.rollback()
        return jsonify(
            {"success": False, "message": f"Error al registrar pago: {str(e)}"}
        ), 500


@apartados_bp.post("/<int:id>/completar")
def completar_apartado(id: int):
    apartado = Apartado.query.get_or_404(id)

    if apartado.estado != "activo":
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400

    if apartado.monto_pagado < apartado.monto_total:
        return jsonify(
            {
                "success": False,
                "message": "El apartado no está completamente pagado",
            }
        ), 400

    try:
        # Obtener tasa actual
        cotizacion_actual = Cotizacion.query.order_by(
            Cotizacion.fecha_hora.desc()
        ).first()
        tasa = (
            cotizacion_actual.tasa_dolar_bolivares if cotizacion_actual else Decimal(0)
        )

        # Crear venta
        venta = Venta(id_cliente=apartado.id_cliente, cotizacion_dolar_bolivares=tasa)
        db.session.add(venta)
        db.session.flush()

        # Crear detalles de venta a partir del apartado
        for detalle_ap in apartado.detalles:
            detalle_venta = DetalleVenta(
                id_venta=venta.id,
                id_producto=detalle_ap.id_producto,
                cantidad=detalle_ap.cantidad,
                precio_unitario_tipo_dolares=detalle_ap.precio_unitario,
                esta_apartado=True,
            )
            db.session.add(detalle_venta)

        apartado.estado = "completado"
        db.session.commit()

        return jsonify(
            {
                "success": True,
                "message": "Apartado completado y venta generada",
                "venta_id": venta.id,
                "apartado": apartado.to_dict(),
            }
        )

    except Exception as e:
        db.session.rollback()
        return jsonify(
            {
                "success": False,
                "message": f"Error al completar apartado: {str(e)}",
            }
        ), 500


@apartados_bp.post("/<int:id>/cancelar")
def cancelar_apartado(id: int):
    apartado = Apartado.query.get_or_404(id)

    if apartado.estado != "activo":
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400

    try:
        # Devolver productos al inventario
        for detalle in apartado.detalles:
            producto = Producto.query.get(detalle.id_producto)
            if producto:
                producto.cantidad_disponible += detalle.cantidad

                # Registrar movimiento de inventario
                movimiento = MovimientoInventario(
                    id_producto=producto.id,
                    tipo="entrada",
                    cantidad=detalle.cantidad,
                    motivo="devolucion",
                    referencia_id=apartado.id,
                    referencia_tipo="apartado",
                    observacion=f"Apartado #{apartado.id} cancelado",
                )
                db.session.add(movimiento)

        apartado.estado = "cancelado"
        db.session.commit()

        return jsonify(
            {
                "success": True,
                "message": "Apartado cancelado. Productos devueltos al inventario.",
                "apartado": apartado.to_dict(),
            }
        )

    except Exception as e:
        db.session.rollback()
        return jsonify(
            {
                "success": False,
                "message": f"Error al cancelar apartado: {str(e)}",
            }
        ), 500


@apartados_bp.get("/<int:id>/pdf")
def generar_pdf_apartado(id: int):
    try:
        apartado = Apartado.query.get_or_404(id)
        apartado_data = apartado.to_dict()

        # Obtener datos del negocio
        negocio = Negocio.query.first()
        if not negocio:
            return jsonify(
                {
                    "success": False,
                    "message": "No hay datos del negocio configurados",
                }
            ), 400

        negocio_data = {
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
        }

        from pdf_generator import generar_apartado_pdf

        pdf_path = generar_apartado_pdf(apartado_data, negocio_data)

        return send_file(
            pdf_path,
            as_attachment=True,
            download_name=os.path.basename(pdf_path),
        )

    except Exception as e:
        return jsonify(
            {"success": False, "message": f"Error al generar PDF: {str(e)}"}
        ), 500


@apartados_bp.delete("/<int:id>")
def delete_apartado(id: int):
    apartado = Apartado.query.get_or_404(id)

    if apartado.estado not in ["cancelado", "completado"]:
        return jsonify(
            {
                "success": False,
                "message": "Solo se pueden eliminar apartados cancelados o completados",
            }
        ), 400

    try:
        db.session.delete(apartado)
        db.session.commit()
        return jsonify({"success": True, "message": "Apartado eliminado correctamente"})
    except Exception as e:
        db.session.rollback()
        return jsonify(
            {"success": False, "message": f"Error al eliminar: {str(e)}"}
        ), 500
