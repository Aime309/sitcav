import sqlite3
from pathlib import Path

import pytest


def test_usuario_id_1_tiene_campos_basicos():
    db_path = Path("instance") / "system_data.db"
    if not db_path.exists():
        pytest.skip("DB not found")

    conn = sqlite3.connect(db_path)
    try:
        cursor = conn.cursor()
        cursor.execute("SELECT id, nombre, cedula, foto_url FROM usuarios WHERE id = 1")
        row = cursor.fetchone()

        if row is None:
            pytest.skip("Usuario con id=1 no encontrado")

        assert row[0] == 1
        assert row[1] is not None and str(row[1]).strip() != ""
        assert row[2] is not None and str(row[2]).strip() != ""
    finally:
        conn.close()
