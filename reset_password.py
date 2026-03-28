from app import app, db, Usuario
from werkzeug.security import generate_password_hash

def reset_password():
    with app.app_context():
        # Encargado
        user = Usuario.query.filter_by(cedula='12345678').first()
        if user:
            user.password = generate_password_hash('test1')
            print(f"Password for {user.nombre} reset to 'test1'")
        
        # Empleado Superior
        user2 = Usuario.query.filter_by(cedula='87654321').first()
        if user2:
            user2.password = generate_password_hash('test1')
            print(f"Password for {user2.nombre} reset to 'test1'")
            
        db.session.commit()

if __name__ == "__main__":
    reset_password()
