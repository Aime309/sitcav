from typing import cast

from flask import Blueprint, current_app, send_from_directory
from markupsafe import escape

uploads_bp = Blueprint(
    name="uploads", import_name=__name__, url_prefix="/uploads"
)


@uploads_bp.get("/productos/<path:filename>")
def select_product_image(filename: str):
    directory = cast(str, current_app.config["PRODUCTS_UPLOAD_FOLDER"])

    return send_from_directory(directory, escape(filename))


@uploads_bp.get("/profiles/<path:filename>")
def select_user_photo(filename: str):
    directory = cast(str, current_app.config["PROFILE_UPLOAD_FOLDER"])

    return send_from_directory(directory, escape(filename))
