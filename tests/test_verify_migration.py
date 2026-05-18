import sqlite3
from pathlib import Path

import pytest


def test_schema_ventas_y_reembolsos_presente():
    db_path = Path("instance") / "system_data.db"
    if not db_path.exists():
        pytest.skip("DB not found")

    conn = sqlite3.connect(db_path)
    try:
        cursor = conn.cursor()

        cursor.execute("PRAGMA table_info(ventas)")
        cols = [column[1] for column in cursor.fetchall()]
        assert "cotizacion_dolar_bolivares" in cols

        cursor.execute(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='reembolsos'"
        )
        assert cursor.fetchone() is not None
    finally:
        conn.close()
