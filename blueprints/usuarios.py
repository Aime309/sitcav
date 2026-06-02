import os
from typing import Any, cast

from flask import Blueprint, current_app, request
from werkzeug.utils import secure_filename

from db import db
from models.usuario import Rol, Usuario

usuarios_bp = Blueprint("usuarios", __name__, url_prefix="/usuarios")


@usuarios_bp.get("/")
def select_users():
    usuarios = Usuario.obtener_todos_los_usuarios()

    return [usuario.to_dict() for usuario in usuarios]


@usuarios_bp.post("/")
def insert_seller():
    json = cast(dict[str, Any], request.get_json())
    cedula = cast(str | None, json.get("cedula"))
    nombres = cast(str | None, json.get("nombre"))
    clave = cast(str, json.get("contrasena", "123456"))
    rol = Usuario.validar_rol(cast(str, json.get("rol", "Empleado")))

    if not cedula or not nombres:
        return {
            "message": "Cédula y nombre son campos obligatorios",
            "success": False,
        }, 400

    if rol is None:
        return {
            "message": f"Rol inválido. Debe ser uno de: {Rol.__args__}",
            "success": False,
        }, 400

    try:
        usuario = Usuario.instanciar_usuario(
            cedula=cedula, nombres=nombres, clave=clave, rol=rol
        )

        db.session.add(usuario)
        db.session.commit()

        return usuario.to_dict(), 201
    except Exception as exception:
        db.session.rollback()

        return {
            "message": f"Error al crear usuario: {exception}",
            "success": False,
        }, 400


@usuarios_bp.put("/<int:id>")
def update_user(id: int):
    usuario = cast(Usuario, Usuario.query.get_or_404(id))
    json = cast(dict[str, Any], request.get_json())
    nombres = cast(str | None, json.get("nombre"))
    cedula = cast(str | None, json.get("cedula"))
    rol = cast(str | None, json.get("rol"))
    activo = cast(bool | None, json.get("activo"))
    apellidos = cast(str | None, json.get("apellidos"))
    direccion = cast(str | None, json.get("direccion"))
    foto_url = cast(str | None, json.get("foto_url"))
    clave = cast(str | None, json.get("contrasena"))
    pregunta_1 = cast(str | None, json.get("pregunta_1"))
    pregunta_2 = cast(str | None, json.get("pregunta_2"))
    pregunta_3 = cast(str | None, json.get("pregunta_3"))
    respuesta_1 = cast(str | None, json.get("respuesta_1"))
    respuesta_2 = cast(str | None, json.get("respuesta_2"))
    respuesta_3 = cast(str | None, json.get("respuesta_3"))

    if rol is not None:
        rol = Usuario.validar_rol(rol)

        if rol is None:
            return {
                "message": f"Rol inválido. Debe ser uno de: {Rol.__args__}",
                "success": False,
            }, 400

    try:
        usuario.actualizar(
            cedula,
            clave,
            rol,
            activo,
            nombres,
            apellidos,
            direccion,
            foto_url,
            pregunta_1,
            pregunta_2,
            pregunta_3,
            respuesta_1,
            respuesta_2,
            respuesta_3,
        )

        db.session.commit()
        return usuario.to_dict()
    except Exception as exception:
        db.session.rollback()
        return {
            "message": f"Error al actualizar usuario: {exception}",
            "success": False,
        }, 400


@usuarios_bp.post("/<int:id>/foto")
def update_user_photo(id: int):
    usuario = cast(Usuario, Usuario.query.get_or_404(id))

    if "foto" not in request.files:
        return {
            "success": False,
            "message": "No se recibió ningún archivo",
        }, 400

    file = request.files["foto"]

    if file.filename == "":
        return {
            "success": False,
            "message": "No se seleccionó ningún archivo",
        }, 400

    if not file:
        return {"success": False, "message": "Error al procesar archivo"}, 400

    try:
        # Create profiles upload directory
        PROFILE_UPLOAD_FOLDER = cast(
            str, current_app.config["PROFILE_UPLOAD_FOLDER"]
        )

        # Generate safe filename
        filename = secure_filename(f"user_{id}_{file.filename}")
        filepath = os.path.join(PROFILE_UPLOAD_FOLDER, filename)

        # Save file
        file.save(filepath)

        # Generate URL
        foto_url = f"/uploads/profiles/{filename}"

        # Update user record
        usuario.actualizar(foto_url=foto_url)
        db.session.commit()

        return {
            "success": True,
            "message": "Foto actualizada correctamente",
            "foto_url": foto_url,
        }
    except Exception as exception:
        db.session.rollback()
        return {
            "success": False,
            "message": f"Error al guardar foto: {exception}",
        }, 500


@usuarios_bp.delete("/<int:id>")
def delete_user(id: int):
    usuario = Usuario.obtener_por_id(id)

    if usuario is None:
        return {"message": "Usuario no encontrado"}, 404

    try:
        db.session.delete(usuario)
        db.session.commit()

        return {"message": "Usuario eliminado con éxito", "success": True}
    except Exception as exception:
        db.session.rollback()

        return {
            "message": f"Error al eliminar usuario: {exception}",
            "success": False,
        }, 500
