import os
from typing import cast

from flask import Flask
from flask_sqlalchemy import SQLAlchemy

from models.base import Base

db = SQLAlchemy(model_class=Base)


def init_db(app: Flask) -> None:
    db.init_app(app)
    SCHEMA_ABS_PATH = cast(str, app.config["SCHEMA_ABS_PATH"])

    with app.app_context():
        if os.path.exists(cast(str, app.config["DATABASE"])):
            return print(
                "[OK] Base de datos existente detectada: "
                + f"{app.config['DATABASE']}."
            )

        with app.open_resource(SCHEMA_ABS_PATH) as resource:
            schema = cast(bytes, resource.read()).decode("utf8")

        # Usar executescript del driver nativo sqlite3 para manejar triggers con punto y coma interno
        raw_connection = db.engine.raw_connection()

        try:
            raw_connection.executescript(schema)
        finally:
            raw_connection.close()

        print(
            "[OK] Base de datos y tablas creadas desde "
            + f"{app.config['SCHEMA_ABS_PATH']}."
        )
