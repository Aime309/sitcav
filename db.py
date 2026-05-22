import os
from datetime import datetime

from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import text

db = SQLAlchemy()


def local_now():
    return datetime.now()


def init_db(app: Flask, db: SQLAlchemy) -> None:
    db.init_app(app)

    with app.app_context():
        if os.path.exists(app.config["DATABASE"]):
            return print("✅ Base de datos existente detectada.")

        with app.open_resource("schemas/sqlite.sql") as f:
            schema_sql = f.read().decode("utf8")

        # Usar executescript del driver nativo sqlite3 para manejar triggers con punto y coma interno
        raw_conn = db.engine.raw_connection()
        try:
            raw_conn.executescript(schema_sql)
        finally:
            raw_conn.close()

        print("✅ Base de datos y tablas creadas desde schemas/sqlite.sql.")
