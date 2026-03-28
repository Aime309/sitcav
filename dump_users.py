from app import app, db, Usuario

def dump_users():
    with app.app_context():
        users = Usuario.query.all()
        with open('users_dump.txt', 'w', encoding='utf-8') as f:
            for u in users:
                f.write(f"ID: {u.id}, Nombre: {u.nombre}, Rol: '{u.rol}'\n")
    print("Users dumped to users_dump.txt")

if __name__ == "__main__":
    dump_users()
