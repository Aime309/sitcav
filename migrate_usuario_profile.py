"""
Script de migración para agregar campos de perfil al modelo Usuario.
Agrega columnas: apellidos, direccion
"""
import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')

if not os.path.exists(db_path):
    print("Base de datos no encontrada. Ejecute la aplicación primero.")
    exit(1)

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Verificar columnas existentes
cursor.execute("PRAGMA table_info(usuarios)")
columns = [col[1] for col in cursor.fetchall()]
print(f"Columnas actuales: {columns}")

migrations_done = []

# Agregar columna apellidos si no existe
if 'apellidos' not in columns:
    try:
        cursor.execute("ALTER TABLE usuarios ADD COLUMN apellidos VARCHAR(100)")
        migrations_done.append('apellidos')
        print("✓ Columna 'apellidos' agregada")
    except Exception as e:
        print(f"Error agregando 'apellidos': {e}")
else:
    print("ℹ️ Columna 'apellidos' ya existe")

# Agregar columna direccion si no existe
if 'direccion' not in columns:
    try:
        cursor.execute("ALTER TABLE usuarios ADD COLUMN direccion VARCHAR(300)")
        migrations_done.append('direccion')
        print("✓ Columna 'direccion' agregada")
    except Exception as e:
        print(f"Error agregando 'direccion': {e}")
else:
    print("ℹ️ Columna 'direccion' ya existe")

conn.commit()
conn.close()

if migrations_done:
    print(f"\n✓ Migración completada. Columnas agregadas: {', '.join(migrations_done)}")
else:
    print("\nℹ️ No se requieren migraciones.")
