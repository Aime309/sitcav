import sqlite3
from pathlib import Path

import pytest


def test_negocios_y_clientes_consultables():
    db_path = Path("instance") / "system_data.db"
    if not db_path.exists():
        pytest.skip("DB not found")

    conn = sqlite3.connect(db_path)
    try:
        cursor = conn.cursor()

        cursor.execute("SELECT id, nombre, direccion FROM negocios")
        negocios = cursor.fetchall()
        assert isinstance(negocios, list)
        assert all(len(row) == 3 for row in negocios)

        cursor.execute("SELECT id, nombre, direccion FROM clientes LIMIT 5")
        clientes = cursor.fetchall()
        assert isinstance(clientes, list)
        assert all(len(row) == 3 for row in clientes)
    finally:
        conn.close()
