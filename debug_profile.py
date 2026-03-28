"""
Script de depuración para verificar el sistema de perfil
"""
import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')
conn = sqlite3.connect(db_path)
c = conn.cursor()

print("=== USUARIOS EN LA BASE DE DATOS ===")
c.execute('SELECT id, nombre, cedula, rol, foto_url FROM usuarios')
for row in c.fetchall():
    print(f"ID: {row[0]}")
    print(f"  Nombre: {row[1]}")
    print(f"  Cedula: {row[2]}")
    print(f"  Rol: {row[3]}")
    print(f"  Foto: {row[4]}")
    print()

print("=== TEST DE ACTUALIZACIÓN DE FOTO ===")
# Intentar actualizar foto del primer usuario
c.execute('UPDATE usuarios SET foto_url = ? WHERE id = 1', ('https://test.com/foto.jpg',))
conn.commit()
c.execute('SELECT foto_url FROM usuarios WHERE id = 1')
result = c.fetchone()
print(f"Foto después de actualizar: {result[0]}")

# Limpiar
c.execute('UPDATE usuarios SET foto_url = NULL WHERE id = 1')
conn.commit()

conn.close()
print("\n✓ Las actualizaciones de base de datos funcionan correctamente")
