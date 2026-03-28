from app import app, db, Usuario

def dump_creds():
    with app.app_context():
        users = Usuario.query.all()
        with open('credentials.txt', 'w', encoding='utf-8') as f:
            for u in users:
                f.write(f"{u.nombre}|{u.cedula}|{u.rol}\n")
    print("Credentials dumped.")

if __name__ == "__main__":
    dump_creds()
