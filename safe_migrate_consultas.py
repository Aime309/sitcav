import sqlite3
import os

DB_PATH = 'instance/system_data.db'

def migrate_db():
    if not os.path.exists(DB_PATH):
        print(f"Database not found at {DB_PATH}")
        return

    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    try:
        # Check if column exists
        cursor.execute("PRAGMA table_info(ventas)")
        columns = [info[1] for info in cursor.fetchall()]
        
        if 'id_vendedor' not in columns:
            print("Adding id_vendedor column to ventas table...")
            cursor.execute("ALTER TABLE ventas ADD COLUMN id_vendedor INTEGER REFERENCES usuarios(id)")
            conn.commit()
            print("Migration successful.")
        else:
            print("Column id_vendedor already exists.")
            
    except Exception as e:
        print(f"Error during migration: {e}")
        conn.rollback()
    finally:
        conn.close()

if __name__ == "__main__":
    migrate_db()
