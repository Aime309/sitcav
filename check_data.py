import sqlite3
import os

def check_data():
    db_path = os.path.join('instance', 'system_data.db')
    if not os.path.exists(db_path):
        print("DB not found")
        return

    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    print("--- Negocio ---")
    try:
        cursor.execute("SELECT id, nombre, direccion FROM negocios")
        rows = cursor.fetchall()
        for r in rows:
            print(f"ID: {r[0]}, Nombre: {r[1]}, Direccion: '{r[2]}'")
    except Exception as e:
        print(f"Error negocios: {e}")

    print("\n--- Clientes (First 5) ---")
    try:
        cursor.execute("SELECT id, nombre, direccion FROM clientes LIMIT 5")
        rows = cursor.fetchall()
        for r in rows:
            print(f"ID: {r[0]}, Nombre: {r[1]}, Direccion: '{r[2]}'")
    except Exception as e:
        print(f"Error clientes: {e}")

    conn.close()

if __name__ == "__main__":
    check_data()
