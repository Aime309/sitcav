from flask import Blueprint, request, session
from werkzeug.security import check_password_hash, generate_password_hash

from db import db
from models.usuario import Usuario

auth_bp = Blueprint(name="auth", import_name=__name__)


@auth_bp.route("/login", methods=["POST", "OPTIONS"])
def login():
    if request.method == "OPTIONS":
        return "", 204

    data = request.get_json()
    cedula = data.get("usuario")
    contrasena = data.get("contrasena")
    usuario = Usuario.query.filter_by(cedula=cedula, activo=True).first()

    if isinstance(usuario, Usuario) and check_password_hash(usuario.contrasena, contrasena):
        print(f"Login exitoso para: {usuario.nombre} con rol: {usuario.rol}")

        session["user_id"] = usuario.id

        return {
            "success": True,
            "message": "Autenticación exitosa",
            "rol": usuario.rol,
            "usuario_id": usuario.id,
            "nombre": usuario.nombre,
            "cedula": usuario.cedula,
            "foto_url": usuario.foto_url,
        }

    print(f"Intento de login fallido para: {cedula}")

    return {"success": False, "message": "Credenciales inválidas"}, 401


@auth_bp.post("/logout")
def logout():
    if "user_id" in session:
        del session["user_id"]

    return {"success": True, "message": "Sesión cerrada correctamente"}


@auth_bp.post("/register")
def register():
    data = request.get_json()

    try:
        # Verificar si la cédula ya existe
        existing_user = Usuario.query.filter_by(cedula=data["cedula"]).first()

        if existing_user:
            return {"success": False, "message": "La cédula ya está registrada"}, 400

        hashed_password = generate_password_hash(data["contrasena"])

        nuevo_usuario = Usuario(
            cedula=data["cedula"],
            nombre=data["nombre"],
            contrasena=hashed_password,
            rol="Vendedor",
            pregunta_1=data.get("pregunta_1"),
            respuesta_1=data.get("respuesta_1"),
            pregunta_2=data.get("pregunta_2"),
            respuesta_2=data.get("respuesta_2"),
            pregunta_3=data.get("pregunta_3"),
            respuesta_3=data.get("respuesta_3"),
        )

        db.session.add(nuevo_usuario)
        db.session.commit()

        return {
            "success": True,
            "message": "Usuario registrado exitosamente",
            "usuario": nuevo_usuario.to_dict(),
        }, 201
    except Exception as exception:
        db.session.rollback()

        return {"success": False, "message": f"Error al registrar: {exception}"}, 400


@auth_bp.post("/check-user-recovery")
def check_user_recovery():
    data = request.get_json()
    cedula = data.get("cedula")
    usuario = Usuario.query.filter_by(cedula=cedula).first()

    if not isinstance(usuario, Usuario):
        return {"success": False, "message": "Usuario no encontrado"}, 404

    # Verificar si tiene preguntas configuradas
    if not usuario.pregunta_1 or not usuario.pregunta_2 or not usuario.pregunta_3:
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
def verify_security_answers():
    data = request.get_json()
    user_id = data.get("user_id")
    respuestas = data.get("respuestas")  # Lista de 3 respuestas

    if not user_id or not respuestas or len(respuestas) != 3:
        return {"success": False, "message": "Datos incompletos"}, 400

    usuario = Usuario.query.get(user_id)

    if not isinstance(usuario, Usuario):
        return {"success": False, "message": "Usuario no encontrado"}, 404

    # Verificar respuestas (ignorando mayúsculas/minúsculas)
    r1_ok = usuario.respuesta_1.lower().strip() == respuestas[0].lower().strip()
    r2_ok = usuario.respuesta_2.lower().strip() == respuestas[1].lower().strip()
    r3_ok = usuario.respuesta_3.lower().strip() == respuestas[2].lower().strip()

    if r1_ok and r2_ok and r3_ok:
        return {"success": True}
    else:
        return {
            "success": False,
            "message": "Una o más respuestas son incorrectas",
        }, 400


@auth_bp.post("/reset-password-recovery")
def reset_password_recovery():
    data = request.get_json()
    user_id = data.get("user_id")
    new_password = data.get("new_password")

    if not user_id or not new_password:
        return {"success": False, "message": "Datos incompletos"}, 400

    usuario = Usuario.query.get(user_id)

    if not usuario:
        return {"success": False, "message": "Usuario no encontrado"}, 404

    try:
        usuario.contrasena = generate_password_hash(new_password)
        db.session.commit()

        return {"success": True, "message": "Contraseña actualizada exitosamente"}
    except Exception as exception:
        db.session.rollback()
        return {"success": False, "message": f"Error: {exception}"}, 500
