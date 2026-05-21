import os

from flask import Blueprint, current_app, jsonify

debug_bp = Blueprint("debug", __name__, url_prefix="/debug")


@debug_bp.get("/uploads")
def list_uploaded_files():
    upload_folder = current_app.config["PRODUCTS_UPLOAD_FOLDER"]
    try:
        files = os.listdir(upload_folder)
        return jsonify(
            {
                "folder": upload_folder,
                "files": files,
                "exists": os.path.exists(upload_folder),
            }
        )
    except Exception as e:
        return jsonify({"error": str(e)})
