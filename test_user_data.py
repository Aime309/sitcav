"""
Test login endpoint to verify it returns cedula and foto_url
"""
import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')
conn = sqlite3.connect(db_path)
c = conn.cursor()

print("=== VERIFICANDO DATOS DEL USUARIO ===")
c.execute('SELECT id, nombre, cedula, foto_url FROM usuarios WHERE id = 1')
row = c.fetchone()
if row:
    print(f"ID: {row[0]}")
    print(f"Nombre: {row[1]}")
    print(f"Cedula: {row[2]}")
    print(f"Foto URL: {row[3]}")
else:
    print("Usuario no encontrado")

conn.close()
