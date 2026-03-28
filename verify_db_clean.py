import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')
conn = sqlite3.connect(db_path)
cursor = conn.cursor()
cursor.execute("PRAGMA table_info(usuarios)")
columns = [info[1] for info in cursor.fetchall()]
for col in columns:
    print(col)
conn.close()
