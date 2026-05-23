import os
from typing import cast

from flask import Flask
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()


def init_db(app: Flask) -> None:
    db.init_app(app)

    with app.app_context():
        if os.path.exists(cast(str, app.config["DATABASE"])):
            return print(
                f"[OK] Base de datos existente detectada: {app.config['DATABASE']}."
            )

        with app.open_resource(cast(str, app.config["SCHEMA_ABS_PATH"])) as f:
            schema_sql = cast(bytes, f.read()).decode("utf8")

        # Usar executescript del driver nativo sqlite3 para manejar triggers con punto y coma interno
        raw_connection = db.engine.raw_connection()

        try:
            raw_connection.executescript(schema_sql)
        finally:
            raw_connection.close()

        print(
            f"[OK] Base de datos y tablas creadas desde {app.config['SCHEMA_ABS_PATH']}."
        )
