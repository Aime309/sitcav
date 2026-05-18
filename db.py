import os
import sqlite3

from flask import Flask

def init_db(app: Flask) -> None:
    if not os.path.exists(app.config["DATABASE"]):
        if not os.path.exists(app.config["SCHEMA_SQL_PATH"]):
            raise FileNotFoundError(
                f"No se encontró el schema SQL en: {app.config['SCHEMA_SQL_PATH']}"
            )

        with sqlite3.connect(app.config["DATABASE"]) as conn:
            with open(
                app.config["SCHEMA_SQL_PATH"], "r", encoding="utf-8"
            ) as schema_file:
                conn.executescript(schema_file.read())
        print("✅ Base de datos y tablas creadas desde schema.sql.")
    else:
        print("✅ Base de datos existente detectada.")
