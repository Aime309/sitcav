import os
from collections.abc import Mapping
from typing import Any

from flask import Flask, render_template

from db import db, init_db


def read_root():
    return render_template("index.html")


def create_app(
    test_config: Mapping[str, Any] | None = None,
) -> Flask:
    # En Vercel, el sistema de archivos es de solo lectura excepto /tmp
    IS_VERCEL = os.environ.get("VERCEL") == "1"

    app = Flask(
        import_name=__name__,
        instance_path="/tmp" if IS_VERCEL else None,
        instance_relative_config=True,
    )

    if test_config is not None:
        app.config.from_mapping(test_config)
    else:
        UPLOADS_ROOT = os.path.join(app.instance_path, "uploads")

        app.config["DATABASE"] = os.path.join(
            app.instance_path, "system_data.db"
        ).replace("\\", "/")

        app.config.from_mapping(
            PRODUCTS_UPLOAD_FOLDER=os.path.join(UPLOADS_ROOT, "products"),
            PROFILE_UPLOAD_FOLDER=os.path.join(UPLOADS_ROOT, "profiles"),
            SQLALCHEMY_DATABASE_URI=f"sqlite:///{app.config['DATABASE']}",
        )

    from blueprints.api import api_bp
    from blueprints.auth import auth_bp
    from blueprints.uploads import uploads_bp

    app.register_blueprint(api_bp)
    app.register_blueprint(auth_bp)
    app.register_blueprint(uploads_bp)

    app.add_url_rule("/", methods=["GET"], view_func=read_root)

    # Configuración de rutas para base de datos y archivos
    if not IS_VERCEL:
        # En desarrollo local, aseguramos que exista la carpeta instance
        if not os.path.exists(app.instance_path):
            os.makedirs(app.instance_path, exist_ok=True)
            os.makedirs(app.config["PRODUCTS_UPLOAD_FOLDER"], exist_ok=True)
            os.makedirs(app.config["PROFILE_UPLOAD_FOLDER"], exist_ok=True)

    init_db(app, db)

    return app


app = create_app()

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5001)
