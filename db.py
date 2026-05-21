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

        with app.open_resource("schema.sql") as f:
            schema = str(f.read().decode("utf8")).split(";")

        with db.engine.connect() as connection:
            for query in schema:
                connection.execute(text(query.strip()))

            print("✅ Base de datos y tablas creadas desde schema.sql.")
