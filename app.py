import os
from collections.abc import Mapping
from typing import Any

from flask import Flask, render_template

from db import init_db


def render_index():
    return render_template("index.html")


def create_app(test_config: Mapping[str, Any] | None = None) -> Flask:
    # En Vercel, el sistema de archivos es de solo lectura excepto /tmp
    IS_VERCEL = os.environ.get("VERCEL") == "1"

    app = Flask(
        import_name=__name__,
        instance_path="/tmp" if IS_VERCEL else None,
        instance_relative_config=True,
    )

    app.secret_key = os.environ.get(
        "SECRET_KEY",
        "75102519e5812b6ab31d592ce67d5c6ddb5ac373c4130f032dc9c5ff4e204de0",
    )

    UPLOADS_ROOT = os.path.join(app.instance_path, "uploads")
    DATABASE = os.path.join(app.instance_path, "system_data.db")
    SCHEMA_REL_PATH = os.path.join("schemas", "sqlite.sql")
    PRODUCTS_UPLOAD_FOLDER = os.path.join(UPLOADS_ROOT, "products")
    PROFILE_UPLOAD_FOLDER = os.path.join(UPLOADS_ROOT, "profiles")

    app.config.from_mapping(
        DATABASE=DATABASE.replace("\\", "/"),
        SCHEMA_REL_PATH=SCHEMA_REL_PATH,
    )

    app.config.from_mapping(
        PRODUCTS_UPLOAD_FOLDER=PRODUCTS_UPLOAD_FOLDER,
        PROFILE_UPLOAD_FOLDER=PROFILE_UPLOAD_FOLDER,
        SQLALCHEMY_DATABASE_URI=f"sqlite:///{app.config['DATABASE']}",
        SCHEMA_ABS_PATH=os.path.join(app.root_path, SCHEMA_REL_PATH),
    )

    if test_config is not None:
        app.config.from_mapping(test_config)

    from blueprints.api import api_bp
    from blueprints.auth import auth_bp
    from blueprints.uploads import uploads_bp

    app.register_blueprint(api_bp)
    app.register_blueprint(auth_bp)
    app.register_blueprint(uploads_bp)

    app.get("/")(render_index)

    # Configuración de rutas para base de datos y archivos
    if not IS_VERCEL:
        # En desarrollo local, aseguramos que exista la carpeta instance
        if not os.path.exists(app.instance_path):
            os.makedirs(app.instance_path, exist_ok=True)
            os.makedirs(PRODUCTS_UPLOAD_FOLDER, exist_ok=True)
            os.makedirs(PROFILE_UPLOAD_FOLDER, exist_ok=True)

    init_db(app)

    return app


app = create_app()

if __name__ == "__main__":
    app.run()
