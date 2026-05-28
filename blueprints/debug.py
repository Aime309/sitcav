import os

from flask import Blueprint, current_app

debug_bp = Blueprint("debug", __name__, url_prefix="/debug")


@debug_bp.get("/uploads")
def select_uploaded_files():
    upload_folder = current_app.config["PRODUCTS_UPLOAD_FOLDER"]
    try:
        files = os.listdir(upload_folder)
        return {
            "folder": upload_folder,
            "files": files,
            "exists": os.path.exists(upload_folder),
        }
    except Exception as e:
        return {"error": str(e)}
