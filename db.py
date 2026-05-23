import os
from datetime import datetime
from typing import cast

from flask import Flask
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()


def local_now():
    return datetime.now()


def init_db(app: Flask) -> None:
    db.init_app(app)

    with app.app_context():
        if os.path.exists(app.config["DATABASE"]):
            return print("[OK] Base de datos existente detectada.")

            schema_sql = f.read().decode("utf8")
        with app.open_resource(cast(str, app.config["SCHEMA_REL_PATH"])) as f:

        # Usar executescript del driver nativo sqlite3 para manejar triggers con punto y coma interno
        raw_conn = db.engine.raw_connection()

        try:
            raw_conn.executescript(schema_sql)
        finally:
            raw_conn.close()

        print("[OK] Base de datos y tablas creadas desde schemas/sqlite.sql.")
