import os
import sqlite3
from datetime import datetime

from flask import Blueprint, current_app, jsonify

backup_bp = Blueprint("backup", __name__, url_prefix="/backup")


@backup_bp.post("/crear")
def crear_backup():
    try:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        backup_filename = f"backup_{timestamp}.sql"
        backup_path = os.path.join(current_app.instance_path, backup_filename)

        db_path = current_app.config["DATABASE"]

        with open(backup_path, "w") as f:
            for line in sqlite3.connect(db_path).iterdump():
                f.write("%s\n" % line)

        return jsonify(
            {
                "success": True,
                "message": "Backup creado exitosamente",
                "filename": backup_filename,
                "path": backup_path,
            }
        )
    except Exception as e:
        return jsonify(
            {"success": False, "message": f"Error al crear backup: {str(e)}"}
        ), 500


@backup_bp.get("/historial")
def historial_backups():
    try:
        backup_dir = current_app.instance_path
        backups = []

        if os.path.exists(backup_dir):
            for filename in os.listdir(backup_dir):
                if filename.startswith("backup_") and filename.endswith(".sql"):
                    filepath = os.path.join(backup_dir, filename)
                    size = os.path.getsize(filepath)
                    backups.append(
                        {
                            "filename": filename,
                            "size": f"{size / 1024:.2f} KB",
                            "fecha": filename.replace("backup_", "").replace(
                                ".sql", ""
                            ),
                        }
                    )

        return jsonify(backups)
    except Exception as e:
        return jsonify(
            {"success": False, "message": f"Error al listar backups: {str(e)}"}
        ), 500


@backup_bp.post("/")
def create_backup():
    import shutil
    from datetime import datetime

    try:
        # Crear directorio de backups si no existe
        backup_dir = os.path.join(current_app.instance_path, "backups")
        os.makedirs(backup_dir, exist_ok=True)

        # Nombre del backup con timestamp
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        backup_filename = f"backup_{timestamp}.db"
        backup_path = os.path.join(backup_dir, backup_filename)

        # Copiar la base de datos
        db_path = current_app.config["DATABASE"]
        shutil.copy2(db_path, backup_path)

        return jsonify(
            {
                "success": True,
                "message": "Backup creado exitosamente",
                "filename": backup_filename,
            }
        )
    except Exception as e:
        return jsonify(
            {"success": False, "message": f"Error al crear backup: {str(e)}"}
        ), 500
