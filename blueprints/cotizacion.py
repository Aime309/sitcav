from datetime import datetime

from flask import Blueprint, request

from models.cotizacion import Cotizacion
from db import db

cotizacion_bp = Blueprint("cotizacion", __name__, url_prefix="/cotizacion")


@cotizacion_bp.get("/actual")
def get_cotizacion_actual():
    try:
        cotizacion = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        if cotizacion:
            return cotizacion.to_dict()
        else:
            # Si no hay cotización, retornar un valor por defecto (ej: 1.0)
            return {
                "tasa_dolar_bolivares": 1.0,
                "fecha_hora": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                "mensaje": "No hay cotizaciones registradas, usando valor por defecto",
            }
    except Exception as e:
        return {"error": str(e)}, 500


@cotizacion_bp.get("/")
def list_cotizaciones():
    try:
        # Limitar a las últimas 50 para no sobrecargar
        cotizaciones = (
            Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).limit(50).all()
        )
        return [c.to_dict() for c in cotizaciones]
    except Exception as e:
        return {"error": str(e)}, 500


@cotizacion_bp.post("/")
def create_cotizacion():
    data = request.get_json()
    try:
        usuario_id = data.get("usuario_id")
        tasa = data.get("tasa")

        if not usuario_id or not tasa:
            return {"success": False, "message": "Faltan datos requeridos"}, 400

        nueva_cotizacion = Cotizacion(
            id_usuario=usuario_id,
            tasa_dolar_bolivares=tasa,
            fecha_hora=datetime.now(),
        )

        db.session.add(nueva_cotizacion)
        db.session.commit()

        return {
            "success": True,
            "message": "Cotización actualizada correctamente",
            "cotizacion": nueva_cotizacion.to_dict(),
        }, 201
    except Exception as e:
        db.session.rollback()
        return {"success": False, "message": str(e)}, 500
