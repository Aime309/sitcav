
import sqlite3
import os

def migrate():
    db_path = os.path.join('instance', 'system_data.db')
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    
    try:
        # Check if column exists
        c.execute("PRAGMA table_info(productos)")
        columns = [column[1] for column in c.fetchall()]
        
        if 'imei' not in columns:
            print("Adding imei column to productos table...")
            c.execute("ALTER TABLE productos ADD COLUMN imei TEXT")
            print("Column added successfully.")
        else:
            print("Column imei already exists.")
            
        conn.commit()
    except Exception as e:
        print(f"Error migrating database: {e}")
        conn.rollback()
    finally:
        conn.close()

if __name__ == "__main__":
    migrate()
