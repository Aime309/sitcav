import sqlite3
import os

# Path to the database
db_path = os.path.join('instance', 'system_data.db')

def migrate():
    if not os.path.exists(db_path):
        print(f"Database not found at {db_path}")
        return

    print(f"Connecting to database at {db_path}...")
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    # Columns to add
    new_columns = [
        ('pregunta_1', 'TEXT'),
        ('respuesta_1', 'TEXT'),
        ('pregunta_2', 'TEXT'),
        ('respuesta_2', 'TEXT'),
        ('pregunta_3', 'TEXT'),
        ('respuesta_3', 'TEXT')
    ]

    table_name = 'usuarios'

    # Get existing columns
    cursor.execute(f"PRAGMA table_info({table_name})")
    existing_columns = [info[1] for info in cursor.fetchall()]
    
    print(f"Existing columns in '{table_name}': {existing_columns}")

    for col_name, col_type in new_columns:
        if col_name not in existing_columns:
            print(f"Adding column {col_name}...")
            try:
                cursor.execute(f"ALTER TABLE {table_name} ADD COLUMN {col_name} {col_type}")
                print(f"Successfully added {col_name}")
            except Exception as e:
                print(f"Error adding {col_name}: {e}")
        else:
            print(f"Column {col_name} already exists.")

    conn.commit()
    conn.close()
    print("Migration completed.")

if __name__ == "__main__":
    migrate()
