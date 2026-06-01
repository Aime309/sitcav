import os
from typing import cast

from flask import Blueprint, current_app

debug_bp = Blueprint(name="debug", import_name=__name__, url_prefix="/debug")


@debug_bp.get("/uploads")
def select_uploaded_files():
    path = cast(str, current_app.config["PRODUCTS_UPLOAD_FOLDER"])

    try:
        files = os.listdir(path)

        return {
            "folder": path,
            "files": files,
            "exists": os.path.exists(path),
        }
    except Exception as exception:
        return {"error": exception}
