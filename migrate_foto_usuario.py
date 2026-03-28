"""
Script de migración para agregar campo foto_url al modelo Usuario.
"""
import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')

if not os.path.exists(db_path):
    print("Base de datos no encontrada.")
    exit(1)

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Verificar columnas existentes
cursor.execute("PRAGMA table_info(usuarios)")
columns = [col[1] for col in cursor.fetchall()]
print(f"Columnas actuales: {columns}")

# Agregar columna foto_url si no existe
if 'foto_url' not in columns:
    try:
        cursor.execute("ALTER TABLE usuarios ADD COLUMN foto_url VARCHAR(500)")
        conn.commit()
        print("✓ Columna 'foto_url' agregada exitosamente")
    except Exception as e:
        print(f"Error: {e}")
else:
    print("ℹ️ Columna 'foto_url' ya existe")

conn.close()
