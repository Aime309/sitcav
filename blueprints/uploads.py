from flask import Blueprint, current_app, send_from_directory
from markupsafe import escape

uploads_bp = Blueprint(name="uploads", import_name=__name__, url_prefix="/uploads")


@uploads_bp.get("/productos/<path:filename>")
def select_product_image(filename: str):
    upload_folder = current_app.config["PRODUCTS_UPLOAD_FOLDER"]


    return send_from_directory(upload_folder, escape(filename))


@uploads_bp.get("/profiles/<path:filename>")
def select_user_photo(filename: str):
    profiles_folder = current_app.config["PROFILE_UPLOAD_FOLDER"]


    return send_from_directory(profiles_folder, escape(filename))
