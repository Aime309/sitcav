import sqlite3
import os

def check_db():
    db_path = os.path.join('instance', 'system_data.db')
    if not os.path.exists(db_path):
        print("DB not found")
        return

    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    # Check ventas
    cursor.execute("PRAGMA table_info(ventas)")
    cols = [c[1] for c in cursor.fetchall()]
    print(f"Ventas columns: {cols}")
    if 'cotizacion_dolar_bolivares' in cols:
        print("✅ cotizacion_dolar_bolivares exists in ventas")
    else:
        print("❌ cotizacion_dolar_bolivares MISSING in ventas")

    # Check reembolsos
    cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name='reembolsos'")
    if cursor.fetchone():
        print("✅ reembolsos table exists")
    else:
        print("❌ reembolsos table MISSING")
        
    conn.close()

if __name__ == '__main__':
    check_db()
