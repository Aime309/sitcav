from flask import Blueprint, current_app, send_from_directory

uploads_bp = Blueprint("uploads", __name__)


@uploads_bp.route("/uploads/productos/<path:filename>")
def uploaded_file(filename: str):
    upload_folder = current_app.config["PRODUCTS_UPLOAD_FOLDER"]
    print(f"DEBUG: Serving file {filename} from {upload_folder}")
    return send_from_directory(upload_folder, filename)


@uploads_bp.route("/uploads/profiles/<path:filename>")
def serve_profile_photo(filename: str):
    profiles_folder = current_app.config["PROFILE_UPLOAD_FOLDER"]
    return send_from_directory(profiles_folder, filename)
