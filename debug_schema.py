import sqlite3
import os

def check_schema():
    db_path = os.path.join('instance', 'system_data.db')
    if not os.path.exists(db_path):
        print(f"❌ Database not found at {db_path}")
        return

    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    print("--- Checking 'ventas' table ---")
    try:
        cursor.execute("PRAGMA table_info(ventas)")
        columns = cursor.fetchall()
        col_names = [c[1] for c in columns]
        print(f"Columns: {col_names}")
        if 'cotizacion_dolar_bolivares' in col_names:
            print("✅ 'cotizacion_dolar_bolivares' exists.")
        else:
            print("❌ 'cotizacion_dolar_bolivares' is MISSING.")
    except Exception as e:
        print(f"Error checking ventas: {e}")

    print("\n--- Checking 'reembolsos' table ---")
    try:
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name='reembolsos'")
        if cursor.fetchone():
            print("✅ 'reembolsos' table exists.")
        else:
            print("❌ 'reembolsos' table is MISSING.")
    except Exception as e:
        print(f"Error checking reembolsos: {e}")
        
    conn.close()

if __name__ == "__main__":
    check_schema()
