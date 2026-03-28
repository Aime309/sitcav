import sqlite3
import os
import sys

# Path to the database
db_path = os.path.join('instance', 'system_data.db')
log_file = 'migration_log.txt'

def log(msg):
    print(msg)
    with open(log_file, 'a', encoding='utf-8') as f:
        f.write(msg + '\n')

def migrate():
    if os.path.exists(log_file):
        os.remove(log_file)
        
    log(f"Starting migration for {db_path}")

    if not os.path.exists(db_path):
        log(f"Database not found at {db_path}")
        return

    try:
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
        
        log(f"Existing columns in '{table_name}': {existing_columns}")

        for col_name, col_type in new_columns:
            if col_name not in existing_columns:
                log(f"Adding column {col_name}...")
                try:
                    cursor.execute(f"ALTER TABLE {table_name} ADD COLUMN {col_name} {col_type}")
                    log(f"Successfully added {col_name}")
                except Exception as e:
                    log(f"Error adding {col_name}: {e}")
            else:
                log(f"Column {col_name} already exists.")

        conn.commit()
        conn.close()
        log("Migration completed.")
    except Exception as e:
        log(f"Fatal error: {e}")

if __name__ == "__main__":
    migrate()
