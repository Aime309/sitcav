from flask import Blueprint, jsonify, request
from werkzeug.security import check_password_hash, generate_password_hash

from models import Usuario, db


auth_bp = Blueprint("auth", __name__)


@auth_bp.route("/login", methods=["POST", "OPTIONS"])
def login():
    if request.method == "OPTIONS":
        return "", 204

    data = request.get_json()
    cedula = data.get("usuario")
    contrasena = data.get("contrasena")

    usuario = Usuario.query.filter_by(cedula=cedula, activo=True).first()

    if usuario and check_password_hash(usuario.contrasena, contrasena):
        print(f"Login exitoso para: {usuario.nombre} con rol: {usuario.rol}")
        return jsonify(
            {
                "success": True,
                "message": "Autenticación exitosa",
                "rol": usuario.rol,
                "usuario_id": usuario.id,
                "nombre": usuario.nombre,
                "cedula": usuario.cedula,
                "foto_url": usuario.foto_url,
            }
        )

    print(f"Intento de login fallido para: {cedula}")
    return jsonify({"success": False, "message": "Credenciales inválidas"}), 401


@auth_bp.route("/logout", methods=["POST"])
def logout():
    return jsonify({"success": True, "message": "Sesión cerrada correctamente"})


@auth_bp.route("/register", methods=["POST"])
def register():
    data = request.get_json()
    try:
        # Verificar si la cédula ya existe
        existing_user = Usuario.query.filter_by(cedula=data["cedula"]).first()
        if existing_user:
            return jsonify(
                {"success": False, "message": "La cédula ya está registrada"}
            ), 400

        hashed_password = generate_password_hash(data["contrasena"])

        nuevo_usuario = Usuario(
            cedula=data["cedula"],
            nombre=data["nombre"],
            contrasena=hashed_password,
            rol="Vendedor",
            activo=True,
            pregunta_1=data.get("pregunta_1"),
            respuesta_1=data.get("respuesta_1"),
            pregunta_2=data.get("pregunta_2"),
            respuesta_2=data.get("respuesta_2"),
            pregunta_3=data.get("pregunta_3"),
            respuesta_3=data.get("respuesta_3"),
        )
        db.session.add(nuevo_usuario)
        db.session.commit()

        return jsonify(
            {
                "success": True,
                "message": "Usuario registrado exitosamente",
                "usuario": nuevo_usuario.to_dict(),
            }
        ), 201
    except Exception as e:
        db.session.rollback()
        return jsonify(
            {"success": False, "message": f"Error al registrar: {str(e)}"}
        ), 400


@auth_bp.route("/check-user-recovery", methods=["POST"])
def check_user_recovery():
    data = request.get_json()
    cedula = data.get("cedula")

    usuario = Usuario.query.filter_by(cedula=cedula).first()
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404

    # Verificar si tiene preguntas configuradas
    if not usuario.pregunta_1 or not usuario.pregunta_2 or not usuario.pregunta_3:
        return jsonify(
            {
                "success": False,
                "message": "El usuario no tiene preguntas de seguridad configuradas. Contacte al administrador.",
            }
        ), 400

    return jsonify(
        {
            "success": True,
            "user_id": usuario.id,
            "preguntas": [
                usuario.pregunta_1,
                usuario.pregunta_2,
                usuario.pregunta_3,
            ],
        }
    )


@auth_bp.route("/verify-security-answers", methods=["POST"])
def verify_security_answers():
    data = request.get_json()
    user_id = data.get("user_id")
    respuestas = data.get("respuestas")  # Lista de 3 respuestas

    if not user_id or not respuestas or len(respuestas) != 3:
        return jsonify({"success": False, "message": "Datos incompletos"}), 400

    usuario = Usuario.query.get(user_id)
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404

    # Verificar respuestas (ignorando mayúsculas/minúsculas)
    r1_ok = usuario.respuesta_1.lower().strip() == respuestas[0].lower().strip()
    r2_ok = usuario.respuesta_2.lower().strip() == respuestas[1].lower().strip()
    r3_ok = usuario.respuesta_3.lower().strip() == respuestas[2].lower().strip()

    if r1_ok and r2_ok and r3_ok:
        return jsonify({"success": True})
    else:
        return jsonify(
            {
                "success": False,
                "message": "Una o más respuestas son incorrectas",
            }
        ), 400


@auth_bp.route("/reset-password-recovery", methods=["POST"])
def reset_password_recovery():
    data = request.get_json()
    user_id = data.get("user_id")
    new_password = data.get("new_password")

    if not user_id or not new_password:
        return jsonify({"success": False, "message": "Datos incompletos"}), 400

    usuario = Usuario.query.get(user_id)
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404

    try:
        usuario.contrasena = generate_password_hash(new_password)
        db.session.commit()
        return jsonify(
            {"success": True, "message": "Contraseña actualizada exitosamente"}
        )
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error: {str(e)}"}), 500
