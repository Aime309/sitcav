import os

from flask import Blueprint, current_app, request
from usuario import Usuario
from werkzeug.security import generate_password_hash
from werkzeug.utils import secure_filename

from db import db

usuarios_bp = Blueprint("usuarios", __name__, url_prefix="/usuarios")


@usuarios_bp.get("/")
def list_usuarios():
    usuarios = Usuario.query.all()
    return [user.to_dict() for user in usuarios]


@usuarios_bp.post("/")
def create_usuario():
    data = request.get_json()
    try:
        hashed_password = generate_password_hash(data.get("contrasena", "123456"))

        nuevo_usuario = Usuario(
            cedula=data["cedula"],
            nombre=data["nombre"],
            contrasena=hashed_password,
            rol=data.get("rol", "Vendedor"),
        )
        db.session.add(nuevo_usuario)
        db.session.commit()
        return nuevo_usuario.to_dict(), 201
    except Exception as e:
        db.session.rollback()
        return {"message": f"Error al crear usuario: {str(e)}", "success": False}, 400


@usuarios_bp.put("/<int:id>")
def update_usuario(id: int):
    usuario = Usuario.query.get_or_404(id)
    data = request.get_json()
    try:
        # Basic info
        usuario.nombre = data.get("nombre", usuario.nombre)
        usuario.cedula = data.get("cedula", usuario.cedula)
        usuario.rol = data.get("rol", usuario.rol)
        usuario.activo = data.get("activo", usuario.activo)

        # Extended profile fields
        if "apellidos" in data:
            usuario.apellidos = data.get("apellidos")
        if "direccion" in data:
            usuario.direccion = data.get("direccion")
        if "foto_url" in data:
            usuario.foto_url = data.get("foto_url")

        # Password update
        if "contrasena" in data and data["contrasena"]:
            usuario.contrasena = generate_password_hash(data["contrasena"])

        # Security questions
        if "pregunta_1" in data:
            usuario.pregunta_1 = data.get("pregunta_1")
        if "respuesta_1" in data:
            usuario.respuesta_1 = data.get("respuesta_1")
        if "pregunta_2" in data:
            usuario.pregunta_2 = data.get("pregunta_2")
        if "respuesta_2" in data:
            usuario.respuesta_2 = data.get("respuesta_2")
        if "pregunta_3" in data:
            usuario.pregunta_3 = data.get("pregunta_3")
        if "respuesta_3" in data:
            usuario.respuesta_3 = data.get("respuesta_3")

        db.session.commit()
        return usuario.to_dict()
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al actualizar usuario: {str(e)}",
            "success": False,
        }, 400


@usuarios_bp.post("/<int:id>/foto")
def upload_usuario_foto(id: int):
    usuario = Usuario.query.get_or_404(id)

    if "foto" not in request.files:
        return {"success": False, "message": "No se recibió ningún archivo"}, 400

    file = request.files["foto"]
    if file.filename == "":
        return {"success": False, "message": "No se seleccionó ningún archivo"}, 400

    if file:
        try:
            # Create profiles upload directory
            profiles_folder = current_app.config["PROFILE_UPLOAD_FOLDER"]
            os.makedirs(profiles_folder, exist_ok=True)

            # Generate safe filename
            filename = secure_filename(f"user_{id}_{file.filename}")
            filepath = os.path.join(profiles_folder, filename)

            # Save file
            file.save(filepath)

            # Generate URL
            foto_url = f"/uploads/profiles/{filename}"

            # Update user record
            usuario.foto_url = foto_url
            db.session.commit()

            return {
                "success": True,
                "message": "Foto actualizada correctamente",
                "foto_url": foto_url,
            }
        except Exception as e:
            db.session.rollback()
            return {
                "success": False,
                "message": f"Error al guardar foto: {str(e)}",
            }, 500

    return {"success": False, "message": "Error al procesar archivo"}, 400


@usuarios_bp.delete("/<int:id>")
def delete_usuario(id: int):
    usuario = Usuario.query.get(id)
    if usuario is None:
        return {"message": "Usuario no encontrado"}, 404

    try:
        db.session.delete(usuario)
        db.session.commit()
        return {"message": "Usuario eliminado con éxito", "success": True}
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar usuario: {str(e)}",
            "success": False,
        }, 500
