from typing import Any, cast

from flask import Blueprint, request, session

from db import db
from models.usuario import Usuario

auth_bp = Blueprint(name="auth", import_name=__name__)


@auth_bp.route("/login", methods=["OPTIONS"])
def login_options():
    return "", 204


@auth_bp.post("/login")
def login():
    json = cast(dict[str, Any], request.get_json())
    cedula = cast(str | None, json.get("usuario"))
    clave = cast(str | None, json.get("contrasena"))

    if not cedula or not clave:
        return {
            "success": False,
            "message": "Cédula y contraseña son requeridos",
        }, 400

    usuario = Usuario.obtener_activos_por_credenciales(cedula, clave)

    if usuario:
        session["usuario.id"] = usuario.id

        return {
            "success": True,
            "message": "Autenticación exitosa",
            "rol": usuario.rol,
            "usuario_id": usuario.id,
            "nombre": usuario.nombre,
            "cedula": usuario.cedula,
            "foto_url": usuario.foto_url,
        }

    return {"success": False, "message": "Credenciales inválidas"}, 401


@auth_bp.post("/logout")
def logout():
    if "usuario.id" in session:
        del session["usuario.id"]

    return {"success": True, "message": "Sesión cerrada correctamente"}


@auth_bp.post("/register")
def insert_seller():
    json = cast(dict[str, Any], request.get_json())
    cedula = cast(str | None, json.get("cedula"))
    clave = cast(str | None, json.get("contrasena"))
    nombres = cast(str | None, json.get("nombre"))
    pregunta_1 = cast(str | None, json.get("pregunta_1"))
    pregunta_2 = cast(str | None, json.get("pregunta_2"))
    pregunta_3 = cast(str | None, json.get("pregunta_3"))
    respuesta_1 = cast(str | None, json.get("respuesta_1"))
    respuesta_2 = cast(str | None, json.get("respuesta_2"))
    respuesta_3 = cast(str | None, json.get("respuesta_3"))

    if (
        not cedula
        or not clave
        or not nombres
        or not pregunta_1
        or not pregunta_2
        or not pregunta_3
        or not respuesta_1
        or not respuesta_2
        or not respuesta_3
    ):
        return {
            "success": False,
            "message": "Todos los campos son requeridos",
        }, 400

    try:
        # Verificar si la cédula ya existe
        usuario = Usuario.obtener_por_cedula(cedula)

        if usuario:
            return {
                "success": False,
                "message": "La cédula ya está registrada",
            }, 400

        vendedor = Usuario.instanciar_empleado(
            cedula,
            nombres,
            clave,
            pregunta_1,
            pregunta_2,
            pregunta_3,
            respuesta_1,
            respuesta_2,
            respuesta_3,
        )

        db.session.add(vendedor)
        db.session.commit()

        return {
            "success": True,
            "message": "Usuario registrado exitosamente",
            "usuario": vendedor.to_dict(),
        }, 201
    except Exception as exception:
        db.session.rollback()

        return {
            "success": False,
            "message": f"Error al registrar: {exception}",
        }, 400


@auth_bp.post("/check-user-recovery")
def check_user_recovery():
    json = cast(dict[str, Any], request.get_json())
    cedula = cast(str | None, json.get("cedula"))

    if not cedula:
        return {"success": False, "message": "Cédula es requerida"}, 400

    usuario = Usuario.obtener_por_cedula(cedula)

    if not usuario:
        return {"success": False, "message": "Usuario no encontrado"}, 404

    # Verificar si tiene preguntas configuradas
    if not usuario.tiene_preguntas():
        return {
            "success": False,
            "message": "El usuario no tiene preguntas de seguridad configuradas. Contacte al administrador.",
        }, 400

    return {
        "success": True,
        "user_id": usuario.id,
        "preguntas": [
            usuario.pregunta_1,
            usuario.pregunta_2,
            usuario.pregunta_3,
        ],
    }


@auth_bp.post("/verify-security-answers")
def check_security_answers():
    json = cast(dict[str, Any], request.get_json())
    id = cast(int | None, json.get("user_id"))
    respuestas = cast(list[str] | None, json.get("respuestas"))

    if not id or not respuestas or len(respuestas) != 3:
        return {"success": False, "message": "Datos incompletos"}, 400

    usuario = Usuario.obtener_por_id(id)

    if not usuario:
        return {"success": False, "message": "Usuario no encontrado"}, 404

    # Verificar respuestas (ignorando mayúsculas/minúsculas)
    if not usuario.verificar_respuestas(respuestas):
        return {"success": True, "message": "Respuestas correctas"}

    return {
        "success": False,
        "message": "Una o más respuestas son incorrectas",
    }, 400


@auth_bp.post("/reset-password-recovery")
def update_user_password():
    json = cast(dict[str, Any], request.get_json())
    id = cast(int | None, json.get("user_id"))
    clave = cast(str | None, json.get("new_password"))

    if not id or not clave:
        return {"success": False, "message": "Datos incompletos"}, 400

    usuario = Usuario.obtener_por_id(id)

    if not usuario:
        return {"success": False, "message": "Usuario no encontrado"}, 404

    try:
        usuario.cambiar_clave(clave)
        db.session.commit()

        return {
            "success": True,
            "message": "Contraseña actualizada exitosamente",
        }
    except Exception as exception:
        db.session.rollback()

        return {"success": False, "message": f"Error: {exception}"}, 500
