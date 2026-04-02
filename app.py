"""
Sistema de Gestión Administrativo - API Backend Completo
Servidor Flask con SQL

Alchemy y todas las funcionalidades requeridas
Versión Consolidada - Incluye todos los endpoints y funcionalidades
"""

import os
import sqlite3
from decimal import Decimal
from datetime import datetime, timedelta
import bcrypt
from flask import Flask, request, jsonify, send_file, send_from_directory
from flask_cors import CORS
from werkzeug.utils import secure_filename
from sqlalchemy.exc import IntegrityError

# Importar modelos
from models import (
    db, Usuario, Cliente, Producto, Categoria, Proveedor,
    Venta, DetalleVenta, Compra, DetalleCompra,
    Pago, TipoPago, Cotizacion,
    Estado, Localidad, Sector, Negocio,
    Apartado, DetalleApartado, PagoApartado, MovimientoInventario, Reembolso
)

def generate_password_hash(password: str):
    return bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt(10))

def check_password_hash(pwHash: bytes, password: bytes) -> bool:
    return bcrypt.checkpw(password, pwHash)

# =====================================================================
# CONFIGURACIÓN DE LA APLICACIÓN
# =====================================================================
app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///system_data.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['SECRET_KEY'] = 'clave-secreta-super-segura-2025'

# Usar ruta absoluta para evitar problemas
basedir = os.path.abspath(os.path.dirname(__file__))
app.config['UPLOAD_FOLDER'] = os.path.join(basedir, 'instance', 'uploads', 'productos')

# Asegurar que existe el directorio
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)

def check_and_migrate_db():
    """Función de auto-migración para asegurar que la BD tenga las columnas necesarias"""
    db_path = os.path.join(basedir, 'instance', 'system_data.db')
    if not os.path.exists(db_path):
        return

    try:
        conn = sqlite3.connect(db_path)
        cursor = conn.cursor()
        
        # 1. Verificar cotizacion_dolar_bolivares en ventas
        cursor.execute("PRAGMA table_info(ventas)")
        columns = [col[1] for col in cursor.fetchall()]
        if 'cotizacion_dolar_bolivares' not in columns:
            print("MIGRATION: Adding cotizacion_dolar_bolivares to ventas...")
            cursor.execute("ALTER TABLE ventas ADD COLUMN cotizacion_dolar_bolivares NUMERIC(10, 2) DEFAULT 0")
            
        # 2. Verificar tabla reembolsos
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name='reembolsos'")
        if not cursor.fetchone():
            print("MIGRATION: Creating reembolsos table...")
            cursor.execute("""
            CREATE TABLE IF NOT EXISTS reembolsos (
                id INTEGER PRIMARY KEY,
                id_venta INTEGER NOT NULL,
                id_usuario INTEGER NOT NULL,
                monto_dolares NUMERIC(10, 2) NOT NULL,
                monto_bolivares NUMERIC(10, 2) NOT NULL,
                tasa_cambio NUMERIC(10, 2) NOT NULL,
                motivo VARCHAR(255),
                fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(id_venta) REFERENCES ventas(id),
                FOREIGN KEY(id_usuario) REFERENCES usuarios(id)
            )
            """)
            
        conn.commit()
        conn.close()
        print("MIGRATION: Database check completed successfully.")
    except Exception as e:
        print(f"MIGRATION ERROR: {e}")

# Ejecutar migración antes de iniciar
check_and_migrate_db()

db.init_app(app)

# =====================================================================
# CRUD: PROVEEDORES
# =====================================================================
@app.route('/api/proveedores', methods=['GET'])
def list_proveedores():
    proveedores = Proveedor.query.all()
    return jsonify([prov.to_dict() for prov in proveedores])

@app.route('/api/proveedores', methods=['POST'])
def create_proveedor():
    data = request.get_json()
    try:
        nuevo_proveedor = Proveedor(
            nombre=data['nombre'],
            rif=data.get('rif'),
            telefono=data.get('telefono'),
            direccion=data.get('direccion'),
            id_estado=data.get('id_estado'),
            id_localidad=data.get('id_localidad'),
            id_sector=data.get('id_sector')
        )
        db.session.add(nuevo_proveedor)
        db.session.commit()
        return jsonify(nuevo_proveedor.to_dict()), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al crear proveedor: {str(e)}", "success": False}), 400

@app.route('/api/proveedores/<int:id>', methods=['PUT'])
def update_proveedor(id):
    proveedor = Proveedor.query.get_or_404(id)
    data = request.get_json()
    try:
        proveedor.nombre = data.get('nombre', proveedor.nombre)
        proveedor.rif = data.get('rif', proveedor.rif)
        proveedor.telefono = data.get('telefono', proveedor.telefono)
        proveedor.direccion = data.get('direccion', proveedor.direccion)
        
        db.session.commit()
        return jsonify(proveedor.to_dict())
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al actualizar proveedor: {str(e)}", "success": False}), 400

@app.route('/api/proveedores/<int:id>', methods=['DELETE'])
def delete_proveedor(id):
    proveedor = Proveedor.query.get(id)
    if proveedor is None:
        return jsonify({"message": "Proveedor no encontrado"}), 404
    
    try:
        db.session.delete(proveedor)
        db.session.commit()
        return jsonify({"message": "Proveedor eliminado con éxito", "success": True})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al eliminar proveedor: {str(e)}", "success": False}), 500

# =====================================================================
# VENTAS
# =====================================================================
@app.route('/api/ventas', methods=['GET'])
def list_ventas():
    ventas = Venta.query.order_by(Venta.fecha_creacion.desc()).all()
    return jsonify([venta.to_dict() for venta in ventas])

@app.route('/api/ventas', methods=['POST'])
def create_venta():
    data = request.get_json()
    try:
        id_cliente = data.get('id_cliente')
        if not id_cliente:
            if 'nuevo_cliente' in data:
                nuevo_cliente = Cliente(
                    nombre=data['nuevo_cliente']['nombre'],
                    apellidos=data['nuevo_cliente'].get('apellidos', ''),
                    cedula=data['nuevo_cliente']['cedula'],
                    telefono=data['nuevo_cliente'].get('telefono')
                )
                db.session.add(nuevo_cliente)
                db.session.flush()
                id_cliente = nuevo_cliente.id
            else:
                return jsonify({"message": "Se requiere un cliente", "success": False}), 400
        
        # Obtener cotización actual
        cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        tasa_cambio = cotizacion_actual.tasa_dolar_bolivares if cotizacion_actual else Decimal('1.00')

        nueva_venta = Venta(
            id_cliente=id_cliente, 
            id_vendedor=data.get('id_vendedor'),
            cotizacion_dolar_bolivares=tasa_cambio
        )
        db.session.add(nueva_venta)
        db.session.flush()
        
        detalles = data.get('detalles', [])
        total_venta = Decimal('0')
        
        for detalle_data in detalles:
            producto = Producto.query.get(detalle_data['id_producto'])
            if not producto:
                raise ValueError(f"Producto {detalle_data['id_producto']} no encontrado")
            
            cantidad = int(detalle_data['cantidad'])
            
            if producto.cantidad_disponible < cantidad:
                raise ValueError(f"Stock insuficiente para {producto.nombre}. Disponible: {producto.cantidad_disponible}")
            
            detalle = DetalleVenta(
                id_venta=nueva_venta.id,
                id_producto=producto.id,
                precio_unitario_tipo_dolares=producto.precio_unitario_actual_dolares,
                cantidad=cantidad,
                esta_apartado=detalle_data.get('esta_apartado', False)
            )
            db.session.add(detalle)
            
            producto.cantidad_disponible -= cantidad
            total_venta += producto.precio_unitario_actual_dolares * cantidad
        
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Venta creada exitosamente",
            "venta": nueva_venta.to_dict(),
            "total": float(total_venta),
            "tasa_cambio": float(tasa_cambio)
        }), 201
        
    except ValueError as ve:
        db.session.rollback()
        return jsonify({"message": str(ve), "success": False}), 400
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al crear venta: {str(e)}", "success": False}), 500

@app.route('/api/ventas/<int:id>', methods=['GET'])
def get_venta(id):
    venta = Venta.query.get_or_404(id)
    return jsonify(venta.to_dict())

@app.route('/api/ventas/<int:id>', methods=['DELETE'])
def delete_venta(id):
    try:
        venta = Venta.query.get_or_404(id)
        
        # Check for associated refunds
        reembolsos = Reembolso.query.filter_by(id_venta=id).count()
        if reembolsos > 0:
            return jsonify({
                "success": False, 
                "message": f"Esta venta tiene {reembolsos} reembolso(s) asociado(s). Debe eliminar los reembolsos primero antes de eliminar la venta.",
                "has_refunds": True
            }), 400
        
        # Restore stock for each product in the sale
        for detalle in venta.detalles:
            producto = Producto.query.get(detalle.id_producto)
            if producto:
                producto.cantidad_disponible += detalle.cantidad
        
        db.session.delete(venta)
        db.session.commit()
        
        return jsonify({"success": True, "message": "Venta eliminada y stock restaurado"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al eliminar venta: {str(e)}", "success": False}), 500


# =====================================================================
# COMPRAS
# =====================================================================
@app.route('/api/compras', methods=['GET'])
def list_compras():
    compras = Compra.query.order_by(Compra.fecha_creacion.desc()).all()
    return jsonify([compra.to_dict() for compra in compras])

@app.route('/api/compras', methods=['POST'])
def create_compra():
    data = request.get_json()
    try:
        cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        if not cotizacion_actual:
            return jsonify({"message": "No hay cotización registrada", "success": False}), 400
        
        nueva_compra = Compra(
            id_proveedor=data['id_proveedor'],
            cotizacion_dolar_bolivares=cotizacion_actual.tasa_dolar_bolivares
        )
        db.session.add(nueva_compra)
        db.session.flush()
        
        detalles = data.get('detalles', [])
        
        for detalle_data in detalles:
            producto = Producto.query.get(detalle_data['id_producto'])
            if not producto:
                raise ValueError(f"Producto {detalle_data['id_producto']} no encontrado")
            
            cantidad = int(detalle_data['cantidad'])
            precio = Decimal(str(detalle_data['precio_unitario']))
            
            detalle = DetalleCompra(
                id_compra=nueva_compra.id,
                id_producto=producto.id,
                precio_unitario_tipo_dolares=precio,
                cantidad=cantidad
            )
            db.session.add(detalle)
            
            producto.cantidad_disponible += cantidad
        
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Compra registrada exitosamente",
            "compra": nueva_compra.to_dict()
        }), 201
        
    except ValueError as ve:
        db.session.rollback()
        return jsonify({"message": str(ve), "success": False}), 400
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al crear compra: {str(e)}", "success": False}), 500

@app.route('/api/compras/<int:id>', methods=['DELETE'])
def delete_compra(id):
    try:
        compra = Compra.query.get_or_404(id)
        
        # Revertir stock
        for detalle in compra.detalles:
            producto = Producto.query.get(detalle.id_producto)
            if producto:
                producto.cantidad_disponible -= detalle.cantidad
        
        db.session.delete(compra)
        db.session.commit()
        
        return jsonify({"success": True, "message": "Compra eliminada y stock revertido"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al eliminar compra: {str(e)}", "success": False}), 500

@app.route('/api/compras/<int:id>/pdf', methods=['GET'])
def get_compra_pdf(id):
    try:
        compra = Compra.query.get_or_404(id)
        negocio = Negocio.query.first()
        
        if not negocio:
            return jsonify({"message": "Datos del negocio no configurados"}), 400
            
        negocio_data = {
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
            "direccion": negocio.direccion
        }
        
        # Enriquecer datos de compra con proveedor
        compra_data = compra.to_dict()
        proveedor = Proveedor.query.get(compra.id_proveedor)
        if proveedor:
            compra_data['proveedor'] = proveedor.to_dict()
            
        # Enriquecer detalles con nombre de producto
        for detalle in compra_data['detalles']:
            prod = Producto.query.get(detalle['id_producto'])
            if prod:
                detalle['producto'] = {'nombre': prod.nombre}
        
        from pdf_generator import generar_factura_compra_pdf
        pdf_path = generar_factura_compra_pdf(compra_data, negocio_data)
        
        return send_file(pdf_path, as_attachment=True)
        
    except Exception as e:
        return jsonify({"message": f"Error al generar PDF: {str(e)}", "success": False}), 500

@app.route('/api/compras/<int:id>', methods=['GET'])
def get_compra(id):
    compra = Compra.query.get_or_404(id)
    return jsonify(compra.to_dict())

# =====================================================================
# PAGOS Y FINANCIAMIENTO
# =====================================================================
@app.route('/api/pagos/venta/<int:venta_id>', methods=['GET'])
def list_pagos_venta(venta_id):
    venta = Venta.query.get_or_404(venta_id)
    pagos = []
    for detalle in venta.detalles:
        for pago in detalle.pagos:
            pagos.append(pago.to_dict())
    return jsonify(pagos)

@app.route('/api/pagos', methods=['POST'])
def create_pago():
    data = request.get_json()
    try:
        cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        if not cotizacion_actual:
            return jsonify({"message": "No hay cotización registrada", "success": False}), 400
        
        nuevo_pago = Pago(
            id_tipo_pago=data['id_tipo_pago'],
            id_detalle_venta=data['id_detalle_venta'],
            monto=Decimal(str(data['monto'])),
            cotizacion_dolar_bolivares=cotizacion_actual.tasa_dolar_bolivares
        )
        db.session.add(nuevo_pago)
        db.session.commit()
        
        return jsonify(nuevo_pago.to_dict()), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al registrar pago: {str(e)}", "success": False}), 400

@app.route('/api/tipos-pago', methods=['GET'])
def list_tipos_pago():
    tipos = TipoPago.query.all()
    return jsonify([tipo.to_dict() for tipo in tipos])



# =====================================================================
# LOCALIZACIÓN
# =====================================================================
@app.route('/api/estados', methods=['GET'])
def list_estados():
    estados = Estado.query.all()
    return jsonify([{"id": e.id, "nombre": e.nombre} for e in estados])

@app.route('/api/localidades/<int:estado_id>', methods=['GET'])
def list_localidades(estado_id):
    localidades = Localidad.query.filter_by(id_estado=estado_id).all()
    return jsonify([{"id": l.id, "nombre": l.nombre} for l in localidades])

@app.route('/api/sectores/<int:localidad_id>', methods=['GET'])
def list_sectores(localidad_id):
    sectores = Sector.query.filter_by(id_localidad=localidad_id).all()
    return jsonify([{"id": s.id, "nombre": s.nombre} for s in sectores])

# =====================================================================
# REPORTES Y ESTAD��STICAS
# =====================================================================
@app.route('/api/reportes/estadisticas', methods=['GET'])
def get_estadisticas():
    try:
        hoy = datetime.now().date()
        ventas_hoy = Venta.query.filter(
            db.func.date(Venta.fecha_creacion) == hoy
        ).count()
        
        productos_bajo_stock = Producto.query.filter(
            Producto.cantidad_disponible < 10
        ).count()
        
        total_clientes = Cliente.query.count()
        
        # Ventas (Totales y del mes)
        total_ventas = Venta.query.count()
        ventas_mes = Venta.query.filter(Venta.fecha_creacion >= inicio_mes).count()
        
        # Nuevos Contadores para Tarjetas de Módulos
        total_empleados = Usuario.query.count()
        total_proveedores = Proveedor.query.count()
        total_compras = Compra.query.count()
        total_apartados_activos = Apartado.query.filter_by(estado='activo').count()
        total_reembolsos = Reembolso.query.count()
        total_inventario_movs = MovimientoInventario.query.count()
        
        cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        tasa_actual = float(cotizacion_actual.tasa_dolar_bolivares) if cotizacion_actual else 0.0
        
        return jsonify({
            "total_productos": total_productos,
            "stock_bajo": stock_bajo,
            "total_clientes": total_clientes,
            "total_ventas": total_ventas,
            "ventas_mes": ventas_mes,
            # Nuevos campos
            "total_empleados": total_empleados,
            "total_proveedores": total_proveedores,
            "total_compras": total_compras,
            "total_apartados_activos": total_apartados_activos,
            "total_reembolsos": total_reembolsos,
            "total_inventario": total_inventario_movs,
            "total_cotizacion": tasa_actual
        })
    except Exception as e:
        return jsonify({"message": f"Error al obtener estadísticas: {str(e)}"}), 500
@app.route('/api/reportes/ventas', methods=['GET'])
def reporte_ventas():
    fecha_desde = request.args.get('desde')
    fecha_hasta = request.args.get('hasta')
    
    query = Venta.query
    
    if fecha_desde:
        query = query.filter(Venta.fecha_creacion >= fecha_desde)
    if fecha_hasta:
        query = query.filter(Venta.fecha_creacion <= fecha_hasta)
    
    ventas = query.order_by(Venta.fecha_creacion.desc()).all()
    
    reporte = []
    total_general = Decimal('0')
    
    for venta in ventas:
        total_venta = sum(d.precio_unitario_tipo_dolares * d.cantidad for d in venta.detalles)
        total_general += total_venta
        
        reporte.append({
            'id': venta.id,
            'fecha': venta.fecha_creacion.strftime('%Y-%m-%d %H:%M'),
            'cliente': f"{venta.cliente.nombre} {venta.cliente.apellidos}",
            'total': float(total_venta)
        })
    
    return jsonify({
        "ventas": reporte,
        "total_general": float(total_general),
        "cantidad_ventas": len(reporte)
    })

# =====================================================================
# BACKUP Y RESTAURACIÓN
# =====================================================================
@app.route('/api/backup/crear', methods=['POST'])
def crear_backup():
    try:
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_filename = f"backup_{timestamp}.sql"
        backup_path = os.path.join('instance', backup_filename)
        
        db_path = os.path.join('instance', 'system_data.db')
        
        with open(backup_path, 'w') as f:
            for line in sqlite3.connect(db_path).iterdump():
                f.write('%s\n' % line)
        
        return jsonify({
            "success": True,
            "message": "Backup creado exitosamente",
            "filename": backup_filename,
            "path": backup_path
        })
    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error al crear backup: {str(e)}"
        }), 500

@app.route('/api/backup/historial', methods=['GET'])
def historial_backups():
    try:
        backup_dir = 'instance'
        backups = []
        
        if os.path.exists(backup_dir):
            for filename in os.listdir(backup_dir):
                if filename.startswith('backup_') and filename.endswith('.sql'):
                    filepath = os.path.join(backup_dir, filename)
                    size = os.path.getsize(filepath)
                    backups.append({
                        'filename': filename,
                        'size': f"{size / 1024:.2f} KB",
                        'fecha': filename.replace('backup_', '').replace('.sql', '')
                    })
        
        return jsonify(backups)
    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error al listar backups: {str(e)}"
        }), 500

# =====================================================================
# CONFIGURACIÓN DEL NEGOCIO
# =====================================================================
@app.route('/api/negocio', methods=['GET'])
def get_negocio():
    negocio = Negocio.query.first()
    if negocio:
        return jsonify({
            "id": negocio.id,
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
            "direccion": negocio.direccion
        })
    return jsonify({"message": "No hay datos del negocio"}), 404

@app.route('/api/negocio', methods=['PUT'])
def update_negocio():
    negocio = Negocio.query.first()
    data = request.get_json()
    
    try:
        if negocio:
            negocio.nombre = data.get('nombre', negocio.nombre)
            negocio.rif = data.get('rif', negocio.rif)
            negocio.telefono = data.get('telefono', negocio.telefono)
            negocio.direccion = data.get('direccion', negocio.direccion)
        else:
            negocio = Negocio(
                nombre=data['nombre'],
                rif=data.get('rif'),
                telefono=data.get('telefono'),
                direccion=data.get('direccion'),
                id_localidad=data.get('id_localidad', 1),
                id_sector=data.get('id_sector', 1)
            )
            db.session.add(negocio)
        
        db.session.commit()
        return jsonify({"success": True, "message": "Datos actualizados"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error: {str(e)}", "success": False}), 400

# =====================================================================
# RECUPERACIÓN DE CONTRASEÑA
# =====================================================================
@app.route('/check-user-recovery', methods=['POST'])
def check_user_recovery():
    data = request.get_json()
    cedula = data.get('cedula')
    
    usuario = Usuario.query.filter_by(cedula=cedula).first()
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404
        
    # Verificar si tiene preguntas configuradas
    if not usuario.pregunta_1 or not usuario.pregunta_2 or not usuario.pregunta_3:
        return jsonify({"success": False, "message": "El usuario no tiene preguntas de seguridad configuradas. Contacte al administrador."}), 400
        
    return jsonify({
        "success": True,
        "user_id": usuario.id,
        "preguntas": [usuario.pregunta_1, usuario.pregunta_2, usuario.pregunta_3]
    })

@app.route('/verify-security-answers', methods=['POST'])
def verify_security_answers():
    data = request.get_json()
    user_id = data.get('user_id')
    respuestas = data.get('respuestas') # Lista de 3 respuestas
    
    if not user_id or not respuestas or len(respuestas) != 3:
        return jsonify({"success": False, "message": "Datos incompletos"}), 400
        
    usuario = Usuario.query.get(user_id)
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404
        
    # Verificar respuestas (ignorando mayúsculas/minúsculas)
    r1_ok = usuario.respuesta_1.lower().strip() == respuestas[0].lower().strip()
    r2_ok = usuario.respuesta_2.lower().strip() == respuestas[1].lower().strip()
    r3_ok = usuario.respuesta_3.lower().strip() == respuestas[2].lower().strip()
    
    if r1_ok and r2_ok and r3_ok:
        return jsonify({"success": True})
    else:
        return jsonify({"success": False, "message": "Una o más respuestas son incorrectas"}), 400

@app.route('/reset-password-recovery', methods=['POST'])
def reset_password_recovery():
    data = request.get_json()
    user_id = data.get('user_id')
    new_password = data.get('new_password')
    
    if not user_id or not new_password:
        return jsonify({"success": False, "message": "Datos incompletos"}), 400
        
    usuario = Usuario.query.get(user_id)
    if not usuario:
        return jsonify({"success": False, "message": "Usuario no encontrado"}), 404
        
    try:
        usuario.contrasena = generate_password_hash(new_password)
        db.session.commit()
        return jsonify({"success": True, "message": "Contraseña actualizada exitosamente"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error: {str(e)}"}), 500

# =====================================================================
# GENERACIÓN DE PDFs
# =====================================================================
from pdf_generator import generar_factura_pdf, generar_reporte_ventas_pdf

@app.route('/api/factura/<int:venta_id>', methods=['GET'])
def generar_factura(venta_id):
    """Genera un PDF de factura para una venta específica"""
    try:
        # Obtener la venta con todos sus datos
        venta = Venta.query.get_or_404(venta_id)
        venta_data = venta.to_dict()
        
        # Obtener datos del negocio
        negocio = Negocio.query.first()
        if not negocio:
            return jsonify({
                "success": False,
                "message": "No hay datos del negocio configurados"
            }), 400
        
        negocio_data = {
            'nombre': negocio.nombre,
            'rif': negocio.rif,
            'telefono': negocio.telefono,
            'direccion': negocio.direccion
        }
        
        # Usar la cotización histórica guardada en la venta (no la actual)
        cotizacion_bs = float(venta.cotizacion_dolar_bolivares) if venta.cotizacion_dolar_bolivares else 35.50
        
        # Generar el PDF
        pdf_path = generar_factura_pdf(venta_data, negocio_data, cotizacion_bs)
        
        # Enviar el archivo
        return send_file(
            pdf_path,
            mimetype='application/pdf',
            as_attachment=True,
            download_name=f'factura_{venta_id}.pdf'
        )
        
    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error al generar factura: {str(e)}"
        }), 500

@app.route('/api/reportes/ventas/pdf', methods=['GET'])
def generar_reporte_ventas_pdf_endpoint():
    """Genera un PDF con reporte de ventas"""
    try:
        fecha_desde = request.args.get('desde')
        fecha_hasta = request.args.get('hasta')
        
        query = Venta.query
        
        if fecha_desde:
            query = query.filter(Venta.fecha_creacion >= fecha_desde)
        if fecha_hasta:
            query = query.filter(Venta.fecha_creacion <= fecha_hasta)
        
        ventas = query.order_by(Venta.fecha_creacion.desc()).all()
        ventas_data = [v.to_dict() for v in ventas]
        
        # Generar el PDF
        pdf_path = generar_reporte_ventas_pdf(ventas_data, fecha_desde, fecha_hasta)
        
        # Enviar el archivo
        return send_file(
            pdf_path,
            mimetype='application/pdf',
            as_attachment=True,
            download_name=f'reporte_ventas.pdf'
        )
        
    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error al generar reporte: {str(e)}"
        }), 500

# =====================================================================
# INICIALIZACIÓN DE LA BASE DE DATOS
# =====================================================================
def initialize_database():
    """Crea la base de datos y añade datos de prueba si no existen"""
    with app.app_context():
        db.create_all()
        print("✅ Base de datos y tablas creadas.")
        
        if Usuario.query.count() > 0:
            print(f"Ya existen {Usuario.query.count()} usuarios en la base de datos.")
            return
        
        print("📊 Añadiendo datos de prueba...")
        
        # USUARIOS
        usuarios_prueba = [
            Usuario(cedula="12345678", nombre="Juan Pérez (Encargado)", contrasena=generate_password_hash("test1"), rol="Encargado", activo=True),
            Usuario(cedula="87654321", nombre="María García (Emp. Superior)", contrasena=generate_password_hash("test1"), rol="Empleado Superior", activo=True),
            Usuario(cedula="11223344", nombre="Carlos López (Vendedor)", contrasena=generate_password_hash("test1"), rol="Vendedor", activo=True)
        ]
        db.session.add_all(usuarios_prueba)
        db.session.flush()
        
        # ESTADOS Y LOCALIDADES
        estado1 = Estado(nombre="Miranda", id_usuario=usuarios_prueba[0].id)
        db.session.add(estado1)
        db.session.flush()
        
        localidad1 = Localidad(nombre="Caracas", id_estado=estado1.id)
        db.session.add(localidad1)
        db.session.flush()
        
        sector1 = Sector(nombre="Centro", id_localidad=localidad1.id)
        db.session.add(sector1)
        db.session.flush()
        
        # NEGOCIO
        negocio = Negocio(nombre="TechStore Venezuela", rif="J-12345678-9", telefono="0212-555-1234", id_localidad=localidad1.id, id_sector=sector1.id)
        db.session.add(negocio)
        
        # CATEGORÍAS
        categorias = [
            Categoria(nombre="Smartphones", id_usuario=usuarios_prueba[0].id),
            Categoria(nombre="Laptops", id_usuario=usuarios_prueba[0].id),
            Categoria(nombre="Accesorios", id_usuario=usuarios_prueba[0].id),
            Categoria(nombre="Tablets", id_usuario=usuarios_prueba[0].id)
        ]
        db.session.add_all(categorias)
        db.session.flush()
        
        # PROVEEDORES
        proveedores = [
            Proveedor(nombre="TechSupply International", rif="J-98765432-1", telefono="0212-555-9876", id_estado=estado1.id, id_localidad=localidad1.id, id_sector=sector1.id),
            Proveedor(nombre="ElectroDistribuidora CA", rif="J-55544433-2", telefono="0212-555-4433", id_estado=estado1.id, id_localidad=localidad1.id)
        ]
        db.session.add_all(proveedores)
        db.session.flush()
        
        # PRODUCTOS
        productos = [
            Producto(nombre="Samsung Galaxy S24", descripcion="Smartphone de última generación", codigo="SAM-S24-001", id_categoria=categorias[0].id, id_proveedor=proveedores[0].id, precio_unitario_actual_dolares=Decimal('899.99'), cantidad_disponible=25, dias_garantia=365, dias_apartado=15, imagen_url="https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400"),
            Producto(nombre="iPhone 15 Pro", descripcion="iPhone con chip A17 Pro", codigo="APL-IP15P-001", id_categoria=categorias[0].id, id_proveedor=proveedores[0].id, precio_unitario_actual_dolares=Decimal('1199.99'), cantidad_disponible=15, dias_garantia=365, dias_apartado=20, imagen_url="https://images.unsplash.com/photo-1592286927505-1a9f33a8441f?w=400"),
            Producto(nombre="Laptop Dell Inspiron 15", descripcion="Laptop para uso profesional", codigo="DELL-INS15-001", id_categoria=categorias[1].id, id_proveedor=proveedores[1].id, precio_unitario_actual_dolares=Decimal('649.99'), cantidad_disponible=10, dias_garantia=730, dias_apartado=30, imagen_url="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400"),
            Producto(nombre="MacBook Air M2", descripcion="Laptop ultraligera de Apple", codigo="APL-MBA-M2-001", id_categoria=categorias[1].id, id_proveedor=proveedores[0].id, precio_unitario_actual_dolares=Decimal('1299.99'), cantidad_disponible=8, dias_garantia=365, dias_apartado=30, imagen_url="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400"),
            Producto(nombre="AirPods Pro 2", descripcion="Audífonos con cancelación de ruido", codigo="APL-APP2-001", id_categoria=categorias[2].id, id_proveedor=proveedores[0].id, precio_unitario_actual_dolares=Decimal('249.99'), cantidad_disponible=50, dias_garantia=365, dias_apartado=7, imagen_url="https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=400"),
            Producto(nombre="Samsung Galaxy Tab S9", descripcion="Tablet Android premium", codigo="SAM-TABS9-001", id_categoria=categorias[3].id, id_proveedor=proveedores[0].id, precio_unitario_actual_dolares=Decimal('799.99'), cantidad_disponible=5, dias_garantia=365, dias_apartado=15, imagen_url="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400")
        ]
        db.session.add_all(productos)
        db.session.flush()
        
        # CLIENTES
        clientes = [
            Cliente(nombre="Ana", apellidos="Rodríguez", cedula="22334455", telefono="0424-111-2222", id_localidad=localidad1.id),
            Cliente(nombre="Pedro", apellidos="Martínez", cedula="33445566", telefono="0414-222-3333", id_localidad=localidad1.id),
            Cliente(nombre="Luisa", apellidos="Fernández", cedula="44556677", telefono="0426-333-4444", id_localidad=localidad1.id)
        ]
        db.session.add_all(clientes)
        db.session.flush()
        
        # COTIZACIÓN INICIAL
        cotizacion_inicial = Cotizacion(tasa_dolar_bolivares=Decimal('35.50'), id_usuario=usuarios_prueba[0].id)
        db.session.add(cotizacion_inicial)
        db.session.flush()
        
        # TIPOS DE PAGO
        tipos_pago = [
            TipoPago(nombre="Efectivo", id_usuario=usuarios_prueba[0].id),
            TipoPago(nombre="Transferencia", id_usuario=usuarios_prueba[0].id),
            TipoPago(nombre="Tarjeta de Débito", id_usuario=usuarios_prueba[0].id),
            TipoPago(nombre="Tarjeta de Crédito", id_usuario=usuarios_prueba[0].id),
            TipoPago(nombre="Pago Móvil", id_usuario=usuarios_prueba[0].id)
        ]
        db.session.add_all(tipos_pago)
        db.session.flush()
        
        # VENTA DE EJEMPLO
        venta_ejemplo = Venta(id_cliente=clientes[0].id)
        db.session.add(venta_ejemplo)
        db.session.flush()
        
        detalle1 = DetalleVenta(id_venta=venta_ejemplo.id, id_producto=productos[0].id, precio_unitario_tipo_dolares=productos[0].precio_unitario_actual_dolares, cantidad=1, esta_apartado=False)
        detalle2 = DetalleVenta(id_venta=venta_ejemplo.id, id_producto=productos[4].id, precio_unitario_tipo_dolares=productos[4].precio_unitario_actual_dolares, cantidad=2, esta_apartado=False)
        db.session.add_all([detalle1, detalle2])
        
        productos[0].cantidad_disponible -= 1
        productos[4].cantidad_disponible -= 2
        
        db.session.commit()
        
        print("✅ Datos de prueba añadidos exitosamente!")
        print("\n📋 USUARIOS CREADOS:")
        print("   Encargado    - Cédula: 12345678 / Contraseña: test1")
        print("   Emp. Superior- Cédula: 87654321 / Contraseña: test1")
        print("   Vendedor     - Cédula: 11223344 / Contraseña: test1")
        print(f"\n💰 Cotización inicial: {cotizacion_inicial.tasa_dolar_bolivares} Bs/USD")
        print(f"📦 Productos creados: {len(productos)}")
        print(f"👥 Clientes creados: {len(clientes)}")
        print(f"🏪 Proveedores creados: {len(proveedores)}")

# =====================================================================
# CRUD: EMPLEADOS
# =====================================================================
@app.route('/api/empleados', methods=['GET'])
def list_empleados():
    try:
        empleados = Usuario.query.all()
        return jsonify([{
            'id': u.id,
            'nombre': u.nombre,
            'cedula': u.cedula,
            'rol': u.rol
        } for u in empleados])
    except Exception as e:
        return jsonify([])

@app.route('/api/empleados/<int:id>', methods=['DELETE'])
def delete_empleado(id):
    empleado = Usuario.query.get(id)
    if empleado is None:
        return jsonify({"message": "Empleado no encontrado"}), 404
    
    try:
        db.session.delete(empleado)
        db.session.commit()
        return jsonify({"message": "Empleado eliminado con éxito", "success": True})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al eliminar empleado: {str(e)}", "success": False}), 500

# =====================================================================
# BACKUP
# =====================================================================
@app.route('/api/backup', methods=['POST'])
def create_backup():
    import shutil
    from datetime import datetime
    
    try:
        # Crear directorio de backups si no existe
        backup_dir = os.path.join(os.path.dirname(__file__), 'backups')
        os.makedirs(backup_dir, exist_ok=True)
        
        # Nombre del backup con timestamp
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        backup_filename = f'backup_{timestamp}.db'
        backup_path = os.path.join(backup_dir, backup_filename)
        
        # Copiar la base de datos
        db_path = os.path.join(os.path.dirname(__file__), 'instance', 'abastos.db')
        shutil.copy2(db_path, backup_path)
        
        return jsonify({
            "success": True,
            "message": "Backup creado exitosamente",
            "filename": backup_filename
        })
    except Exception as e:
        return jsonify({
            "success": False,
            "message": f"Error al crear backup: {str(e)}"
        }), 500

# =====================================================================
# APARTADOS (Sistema de Layaway)
# =====================================================================
@app.route('/api/apartados', methods=['GET'])
def list_apartados():
    """Lista todos los apartados con filtro opcional por estado"""
    estado = request.args.get('estado', None)
    query = Apartado.query
    if estado:
        query = query.filter_by(estado=estado)
    apartados = query.order_by(Apartado.fecha_creacion.desc()).all()
    return jsonify([a.to_dict() for a in apartados])

@app.route('/api/apartados', methods=['POST'])
def create_apartado():
    """Crea un nuevo apartado con múltiples productos"""
    data = request.get_json()
    try:
        from datetime import timedelta
        
        id_cliente = data.get('id_cliente')
        if not id_cliente:
            return jsonify({"success": False, "message": "Se requiere un cliente"}), 400
        
        productos = data.get('productos', [])
        if not productos:
            return jsonify({"success": False, "message": "Se requiere al menos un producto"}), 400
        
        # Calcular monto total y validar stock
        monto_total = Decimal('0')
        for item in productos:
            producto = Producto.query.get(item['id_producto'])
            if not producto:
                return jsonify({"success": False, "message": f"Producto {item['id_producto']} no encontrado"}), 404
            if producto.cantidad_disponible < item['cantidad']:
                return jsonify({"success": False, "message": f"Stock insuficiente para {producto.nombre}"}), 400
            monto_total += Decimal(str(producto.precio_unitario_actual_dolares)) * item['cantidad']
        
        # Calcular fecha límite (3 meses por defecto)
        dias_limite = data.get('dias_limite', 90)
        fecha_limite = datetime.now() + timedelta(days=dias_limite)
        
        # Crear apartado
        nuevo_apartado = Apartado(
            id_cliente=id_cliente,
            fecha_limite=fecha_limite,
            monto_total=monto_total,
            monto_pagado=Decimal('0'),
            estado='activo',
            observaciones=data.get('observaciones', '')
        )
        db.session.add(nuevo_apartado)
        db.session.flush()
        
        # Crear detalles y reducir stock
        for item in productos:
            producto = Producto.query.get(item['id_producto'])
            
            detalle = DetalleApartado(
                id_apartado=nuevo_apartado.id,
                id_producto=producto.id,
                cantidad=item['cantidad'],
                precio_unitario=producto.precio_unitario_actual_dolares
            )
            db.session.add(detalle)
            
            # Reducir stock
            producto.cantidad_disponible -= item['cantidad']
            
            # Registrar movimiento de inventario
            movimiento = MovimientoInventario(
                id_producto=producto.id,
                tipo='salida',
                cantidad=item['cantidad'],
                motivo='apartado',
                referencia_id=nuevo_apartado.id,
                referencia_tipo='apartado',
                observacion=f"Apartado #{nuevo_apartado.id} creado"
            )
            db.session.add(movimiento)
        
        # Registrar pago inicial si se proporciona
        abono_inicial = data.get('abono_inicial', 0)
        if abono_inicial and float(abono_inicial) > 0:
            pago = PagoApartado(
                id_apartado=nuevo_apartado.id,
                monto=Decimal(str(abono_inicial)),
                observacion='Abono inicial'
            )
            db.session.add(pago)
            nuevo_apartado.monto_pagado = Decimal(str(abono_inicial))
        
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Apartado creado exitosamente",
            "apartado": nuevo_apartado.to_dict()
        }), 201
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error al crear apartado: {str(e)}"}), 500

@app.route('/api/apartados/<int:id>', methods=['GET'])
def get_apartado(id):
    """Obtiene un apartado específico con todos sus detalles"""
    apartado = Apartado.query.get_or_404(id)
    return jsonify(apartado.to_dict())

@app.route('/api/apartados/<int:id>/pago', methods=['POST'])
def registrar_pago_apartado(id):
    """Registra un pago/abono a un apartado"""
    apartado = Apartado.query.get_or_404(id)
    
    if apartado.estado != 'activo':
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400
    
    data = request.get_json()
    monto = Decimal(str(data.get('monto', 0)))
    
    if monto <= 0:
        return jsonify({"success": False, "message": "El monto debe ser mayor a 0"}), 400
    
    try:
        # Registrar pago
        pago = PagoApartado(
            id_apartado=apartado.id,
            monto=monto,
            observacion=data.get('observacion', '')
        )
        db.session.add(pago)
        
        # Actualizar monto pagado
        apartado.monto_pagado += monto
        
        # Verificar si está completamente pagado
        if apartado.monto_pagado >= apartado.monto_total:
            apartado.estado = 'completado'
        
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Pago registrado exitosamente",
            "apartado": apartado.to_dict()
        })
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error al registrar pago: {str(e)}"}), 500

@app.route('/api/apartados/<int:id>/completar', methods=['POST'])
def completar_apartado(id):
    """Completa un apartado y genera una venta"""
    apartado = Apartado.query.get_or_404(id)
    
    if apartado.estado != 'activo':
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400
    
    if apartado.monto_pagado < apartado.monto_total:
        return jsonify({"success": False, "message": "El apartado no está completamente pagado"}), 400
    
    try:
        # Obtener tasa actual
        cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        tasa = cotizacion_actual.tasa_dolar_bolivares if cotizacion_actual else Decimal(0)

        # Crear venta
        venta = Venta(
            id_cliente=apartado.id_cliente,
            cotizacion_dolar_bolivares=tasa
        )
        db.session.add(venta)
        db.session.flush()
        
        # Crear detalles de venta a partir del apartado
        for detalle_ap in apartado.detalles:
            detalle_venta = DetalleVenta(
                id_venta=venta.id,
                id_producto=detalle_ap.id_producto,
                cantidad=detalle_ap.cantidad,
                precio_unitario_tipo_dolares=detalle_ap.precio_unitario,
                esta_apartado=True
            )
            db.session.add(detalle_venta)
        
        apartado.estado = 'completado'
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Apartado completado y venta generada",
            "venta_id": venta.id,
            "apartado": apartado.to_dict()
        })
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error al completar apartado: {str(e)}"}), 500

@app.route('/api/apartados/<int:id>/cancelar', methods=['POST'])
def cancelar_apartado(id):
    """Cancela un apartado y devuelve productos al inventario"""
    apartado = Apartado.query.get_or_404(id)
    
    if apartado.estado != 'activo':
        return jsonify({"success": False, "message": "El apartado no está activo"}), 400
    
    try:
        # Devolver productos al inventario
        for detalle in apartado.detalles:
            producto = Producto.query.get(detalle.id_producto)
            if producto:
                producto.cantidad_disponible += detalle.cantidad
                
                # Registrar movimiento de inventario
                movimiento = MovimientoInventario(
                    id_producto=producto.id,
                    tipo='entrada',
                    cantidad=detalle.cantidad,
                    motivo='devolucion',
                    referencia_id=apartado.id,
                    referencia_tipo='apartado',
                    observacion=f"Apartado #{apartado.id} cancelado"
                )
                db.session.add(movimiento)
        
        apartado.estado = 'cancelado'
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Apartado cancelado. Productos devueltos al inventario.",
            "apartado": apartado.to_dict()
        })
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error al cancelar apartado: {str(e)}"}), 500

@app.route('/api/apartados/<int:id>/pdf', methods=['GET'])
def generar_pdf_apartado(id):
    """Genera un PDF del apartado"""
    try:
        apartado = Apartado.query.get_or_404(id)
        apartado_data = apartado.to_dict()
        
        # Obtener datos del negocio
        negocio = Negocio.query.first()
        if not negocio:
            return jsonify({"success": False, "message": "No hay datos del negocio configurados"}), 400
        
        negocio_data = {
            'nombre': negocio.nombre,
            'rif': negocio.rif,
            'telefono': negocio.telefono
        }
        
        from pdf_generator import generar_apartado_pdf
        pdf_path = generar_apartado_pdf(apartado_data, negocio_data)
        
        return send_file(pdf_path, as_attachment=True, download_name=os.path.basename(pdf_path))
        
    except Exception as e:
        return jsonify({"success": False, "message": f"Error al generar PDF: {str(e)}"}), 500

@app.route('/api/apartados/<int:id>', methods=['DELETE'])
def delete_apartado(id):
    """Elimina un apartado (solo si está cancelado)"""
    apartado = Apartado.query.get_or_404(id)
    
    if apartado.estado not in ['cancelado', 'completado']:
        return jsonify({"success": False, "message": "Solo se pueden eliminar apartados cancelados o completados"}), 400
    
    try:
        db.session.delete(apartado)
        db.session.commit()
        return jsonify({"success": True, "message": "Apartado eliminado correctamente"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error al eliminar: {str(e)}"}), 500


# =====================================================================
# INVENTARIO
# =====================================================================
@app.route('/api/inventario', methods=['GET'])
def list_inventario():
    """Lista productos con información de stock"""
    productos = Producto.query.all()
    resultado = []
    
    for prod in productos:
        # Calcular cantidad apartada
        cantidad_apartada = 0
        for detalle in prod.detalles_apartados:
            if detalle.apartado.estado == 'activo':
                cantidad_apartada += detalle.cantidad
        
        prod_dict = prod.to_dict()
        prod_dict['cantidad_apartada'] = cantidad_apartada
        prod_dict['cantidad_total'] = prod.cantidad_disponible + cantidad_apartada
        if prod.categoria:
            prod_dict['categoria_nombre'] = prod.categoria.nombre
        resultado.append(prod_dict)
    
    return jsonify(resultado)

@app.route('/api/inventario/movimientos', methods=['GET'])
def list_movimientos():
    """Lista el historial de movimientos de inventario"""
    id_producto = request.args.get('id_producto', None)
    tipo = request.args.get('tipo', None)
    
    query = MovimientoInventario.query
    
    if id_producto:
        query = query.filter_by(id_producto=id_producto)
    if tipo:
        query = query.filter_by(tipo=tipo)
    
    movimientos = query.order_by(MovimientoInventario.fecha.desc()).limit(100).all()
    return jsonify([m.to_dict() for m in movimientos])

@app.route('/api/inventario/ajuste', methods=['POST'])
def ajuste_inventario():
    """Realiza un ajuste manual de inventario"""
    data = request.get_json()
    
    id_producto = data.get('id_producto')
    cantidad = int(data.get('cantidad', 0))
    tipo = data.get('tipo')  # entrada o salida
    observacion = data.get('observacion', 'Ajuste manual')
    
    if not id_producto or not tipo or cantidad <= 0:
        return jsonify({"success": False, "message": "Datos incompletos"}), 400
    
    producto = Producto.query.get(id_producto)
    if not producto:
        return jsonify({"success": False, "message": "Producto no encontrado"}), 404
    
    try:
        if tipo == 'entrada':
            producto.cantidad_disponible += cantidad
        elif tipo == 'salida':
            if producto.cantidad_disponible < cantidad:
                return jsonify({"success": False, "message": "Stock insuficiente"}), 400
            producto.cantidad_disponible -= cantidad
        else:
            return jsonify({"success": False, "message": "Tipo debe ser 'entrada' o 'salida'"}), 400
        
        # Registrar movimiento
        movimiento = MovimientoInventario(
            id_producto=producto.id,
            tipo=tipo,
            cantidad=cantidad,
            motivo='ajuste_manual',
            observacion=observacion
        )
        db.session.add(movimiento)
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Ajuste realizado exitosamente",
            "producto": producto.to_dict(),
            "movimiento": movimiento.to_dict()
        })
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": f"Error: {str(e)}"}), 500


# =====================================================================
# MÓDULO DE CONSULTAS
# =====================================================================
@app.route('/api/consultas/ventas', methods=['GET'])
def consultar_ventas():
    """Consulta avanzada de ventas con filtros"""
    try:
        id_vendedor = request.args.get('id_vendedor')
        id_cliente = request.args.get('id_cliente')
        fecha_desde = request.args.get('fecha_desde')
        fecha_hasta = request.args.get('fecha_hasta')
        
        query = Venta.query
        
        if id_vendedor:
            query = query.filter(Venta.id_vendedor == id_vendedor)
        if id_cliente:
            query = query.filter(Venta.id_cliente == id_cliente)
        if fecha_desde:
            query = query.filter(Venta.fecha_creacion >= fecha_desde)
        if fecha_hasta:
            # Ajustar para incluir todo el día hasta
            query = query.filter(Venta.fecha_creacion <= f"{fecha_hasta} 23:59:59")
            
        ventas = query.order_by(Venta.fecha_creacion.desc()).all()
        
        resultado = []
        for v in ventas:
            v_dict = v.to_dict()
            # Agregar datos del vendedor si existe
            if v.id_vendedor:
                vendedor = Usuario.query.get(v.id_vendedor)
                if vendedor:
                    v_dict['vendedor'] = {'id': vendedor.id, 'nombre': vendedor.nombre}
            else:
                v_dict['vendedor'] = {'id': None, 'nombre': 'Sin asignar'}
                
            # Calcular total
            total = sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles)
            v_dict['total'] = float(total)
            
            resultado.append(v_dict)
            
        return jsonify(resultado)
    except Exception as e:
        return jsonify({"success": False, "message": f"Error al consultar: {str(e)}"}), 500

@app.route('/api/consultas/ventas/pdf', methods=['GET'])
def exportar_consultas_pdf():
    """Genera PDF de la consulta actual"""
    try:
        # Reutilizar lógica de filtros
        id_vendedor = request.args.get('id_vendedor')
        id_cliente = request.args.get('id_cliente')
        fecha_desde = request.args.get('fecha_desde')
        fecha_hasta = request.args.get('fecha_hasta')
        
        query = Venta.query
        filtros_texto = []
        
        if id_vendedor:
            query = query.filter(Venta.id_vendedor == id_vendedor)
            vend = Usuario.query.get(id_vendedor)
            filtros_texto.append(f"Vendedor: {vend.nombre if vend else id_vendedor}")
            
        if id_cliente:
            query = query.filter(Venta.id_cliente == id_cliente)
            cli = Cliente.query.get(id_cliente)
            filtros_texto.append(f"Cliente: {cli.nombre if cli else id_cliente}")
            
        if fecha_desde:
            query = query.filter(Venta.fecha_creacion >= fecha_desde)
            filtros_texto.append(f"Desde: {fecha_desde}")
            
        if fecha_hasta:
            query = query.filter(Venta.fecha_creacion <= f"{fecha_hasta} 23:59:59")
            filtros_texto.append(f"Hasta: {fecha_hasta}")
            
        ventas = query.order_by(Venta.fecha_creacion.desc()).all()
        
        # Preparar datos para el PDF
        datos_reporte = []
        for v in ventas:
            total = sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles)
            vendedor_nombre = "Sin asignar"
            if v.id_vendedor:
                vend = Usuario.query.get(v.id_vendedor)
                if vend: vendedor_nombre = vend.nombre
                
            datos_reporte.append({
                'id': v.id,
                'fecha': v.fecha_creacion.strftime('%d/%m/%Y %H:%M'),
                'cliente': f"{v.cliente.nombre} {v.cliente.apellidos}",
                'vendedor': vendedor_nombre,
                'total': float(total)
            })
            
        from pdf_generator import generar_reporte_consultas_pdf
        pdf_path = generar_reporte_consultas_pdf(datos_reporte, filtros_texto)
        
        return send_file(pdf_path, as_attachment=True, download_name="reporte_consultas.pdf")
        
    except Exception as e:
        return jsonify({"success": False, "message": f"Error al generar PDF: {str(e)}"}), 500



# =====================================================================
# COTIZACIONES
# =====================================================================
@app.route('/api/cotizacion/actual', methods=['GET'])
def get_cotizacion_actual():
    """Obtiene la cotización más reciente"""
    try:
        cotizacion = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
        if cotizacion:
            return jsonify(cotizacion.to_dict())
        else:
            # Si no hay cotización, retornar un valor por defecto (ej: 1.0)
            return jsonify({
                "tasa_dolar_bolivares": 1.0,
                "fecha_hora": datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
                "mensaje": "No hay cotizaciones registradas, usando valor por defecto"
            })
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/api/cotizacion', methods=['GET'])
def list_cotizaciones():
    """Lista el historial de cotizaciones"""
    try:
        # Limitar a las últimas 50 para no sobrecargar
        cotizaciones = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).limit(50).all()
        return jsonify([c.to_dict() for c in cotizaciones])
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/api/cotizacion', methods=['POST'])
def create_cotizacion():
    """Registra una nueva cotización"""
    data = request.get_json()
    try:
        usuario_id = data.get('usuario_id')
        tasa = data.get('tasa')
        
        if not usuario_id or not tasa:
            return jsonify({"success": False, "message": "Faltan datos requeridos"}), 400
            
        nueva_cotizacion = Cotizacion(
            id_usuario=usuario_id,
            tasa_dolar_bolivares=tasa,
            fecha_hora=datetime.now()
        )
        
        db.session.add(nueva_cotizacion)
        db.session.commit()
        
        return jsonify({
            "success": True, 
            "message": "Cotización actualizada correctamente",
            "cotizacion": nueva_cotizacion.to_dict()
        }), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"success": False, "message": str(e)}), 500


# =====================================================================
# REEMBOLSOS
# =====================================================================
@app.route('/api/reembolsos', methods=['GET'])
def get_reembolsos():
    try:
        reembolsos = Reembolso.query.order_by(Reembolso.fecha.desc()).all()
        return jsonify([r.to_dict() for r in reembolsos])
    except Exception as e:
        return jsonify({"message": f"Error al obtener reembolsos: {str(e)}", "success": False}), 500

@app.route('/api/reembolsos', methods=['POST'])
def create_reembolso():
    data = request.get_json()
    try:
        id_venta = data.get('id_venta')
        id_usuario = data.get('id_usuario')
        monto_dolares = Decimal(str(data.get('monto_dolares')))
        motivo = data.get('motivo')
        
        venta = Venta.query.get(id_venta)
        if not venta:
            return jsonify({"message": "Venta no encontrada", "success": False}), 404
            
        # Usar la tasa histórica de la venta
        tasa_cambio = venta.cotizacion_dolar_bolivares
        if not tasa_cambio or tasa_cambio == 0:
             # Fallback si es una venta antigua sin tasa guardada
            cotizacion_actual = Cotizacion.query.order_by(Cotizacion.fecha_hora.desc()).first()
            tasa_cambio = cotizacion_actual.tasa_dolar_bolivares if cotizacion_actual else Decimal('1.00')
            
        monto_bolivares = monto_dolares * tasa_cambio
        
        nuevo_reembolso = Reembolso(
            id_venta=id_venta,
            id_usuario=id_usuario,
            monto_dolares=monto_dolares,
            monto_bolivares=monto_bolivares,
            tasa_cambio=tasa_cambio,
            motivo=motivo
        )
        
        db.session.add(nuevo_reembolso)
        db.session.commit()
        
        return jsonify({
            "success": True,
            "message": "Reembolso procesado exitosamente",
            "reembolso": nuevo_reembolso.to_dict()
        }), 201
        
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al procesar reembolso: {str(e)}", "success": False}), 500

@app.route('/api/reembolsos/<int:id>', methods=['DELETE'])
def delete_reembolso(id):
    try:
        reembolso = Reembolso.query.get_or_404(id)
        db.session.delete(reembolso)
        db.session.commit()
        return jsonify({"success": True, "message": "Reembolso eliminado correctamente"})
    except Exception as e:
        db.session.rollback()
        return jsonify({"message": f"Error al eliminar reembolso: {str(e)}", "success": False}), 500

@app.route('/api/reembolsos/<int:id>/pdf', methods=['GET'])
def get_reembolso_pdf(id):
    try:
        reembolso = Reembolso.query.get_or_404(id)
        venta = Venta.query.get(reembolso.id_venta)
        usuario = Usuario.query.get(reembolso.id_usuario)
        negocio = Negocio.query.first()
        
        # Create a simple PDF receipt for the refund
        from reportlab.lib.pagesizes import letter
        from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
        from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
        from reportlab.lib import colors
        from reportlab.lib.units import inch
        from reportlab.lib.enums import TA_CENTER
        import os
        from datetime import datetime
        
        # Create folder
        reembolsos_dir = os.path.join('instance', 'reembolsos_pdf')
        os.makedirs(reembolsos_dir, exist_ok=True)
        
        filename = f"reembolso_{id}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pdf"
        filepath = os.path.join(reembolsos_dir, filename)
        
        doc = SimpleDocTemplate(filepath, pagesize=letter)
        elements = []
        styles = getSampleStyleSheet()
        
        title_style = ParagraphStyle('Title', parent=styles['Heading1'], fontSize=20, textColor=colors.HexColor('#dc3545'), alignment=TA_CENTER)
        
        elements.append(Paragraph(negocio.nombre if negocio else "Negocio", title_style))
        elements.append(Paragraph(f"Comprobante de Reembolso #{id}", styles['Heading2']))
        elements.append(Spacer(1, 0.3*inch))
        
        data = [
            ['Fecha:', reembolso.fecha.strftime('%d/%m/%Y %H:%M')],
            ['Venta Original:', f"#{reembolso.id_venta}"],
            ['Procesado por:', usuario.nombre if usuario else 'N/A'],
            ['Monto (USD):', f"${float(reembolso.monto_dolares):.2f}"],
            ['Monto (Bs):', f"{float(reembolso.monto_bolivares):,.2f} Bs"],
            ['Tasa de Cambio:', f"{float(reembolso.tasa_cambio):.2f} Bs/$"],
            ['Motivo:', reembolso.motivo or '-']
        ]
        
        table = Table(data, colWidths=[2*inch, 4*inch])
        table.setStyle(TableStyle([
            ('BACKGROUND', (0, 0), (0, -1), colors.HexColor('#f8d7da')),
            ('FONTNAME', (0, 0), (0, -1), 'Helvetica-Bold'),
            ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
            ('PADDING', (0, 0), (-1, -1), 8),
        ]))
        
        elements.append(table)
        elements.append(Spacer(1, 0.5*inch))
        elements.append(Paragraph("Este documento es un comprobante de reembolso.", styles['Normal']))
        
        doc.build(elements)
        
        return send_file(filepath, as_attachment=True, download_name=f'reembolso_{id}.pdf')
        
    except Exception as e:
        return jsonify({"message": f"Error al generar PDF: {str(e)}", "success": False}), 500


# =====================================================================
# ESTADÍSTICAS
# =====================================================================

@app.route('/api/estadisticas/resumen', methods=['GET'])
def get_estadisticas_resumen():
    """Retorna KPIs del día actual"""
    try:
        hoy = datetime.now().date()
        inicio_dia = datetime.combine(hoy, datetime.min.time())
        fin_dia = datetime.combine(hoy, datetime.max.time())
        
        # 1. Ventas de Hoy
        ventas_hoy = Venta.query.filter(Venta.fecha_creacion.between(inicio_dia, fin_dia)).all()
        total_ventas_hoy = sum(sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles) for v in ventas_hoy)
        cantidad_ventas_hoy = len(ventas_hoy)
        
        # 2. Compras de Hoy (Gastos)
        compras_hoy = Compra.query.filter(Compra.fecha_creacion.between(inicio_dia, fin_dia)).all()
        # Fix: use precio_unitario_tipo_dolares instead of precio_unitario
        total_compras_hoy = sum(sum(d.precio_unitario_tipo_dolares * d.cantidad for d in c.detalles) for c in compras_hoy)
        
        # 3. Productos Stock Bajo
        stock_bajo = Producto.query.filter(Producto.cantidad_disponible <= 5).count()
        
        # 4. Beneficio Estimado (Ventas - Compras)
        beneficio_hoy = total_ventas_hoy - total_compras_hoy
        
        # 5. Apartados Activos
        apartados_activos = Apartado.query.filter_by(estado='activo').count()
        
        return jsonify({
            "ventas_hoy_monto": float(total_ventas_hoy),
            "ventas_hoy_cantidad": cantidad_ventas_hoy,
            "compras_hoy_monto": float(total_compras_hoy),
            "stock_bajo_count": stock_bajo,
            "beneficio_hoy": float(beneficio_hoy),
            "apartados_activos": apartados_activos
        })
    except Exception as e:
        return jsonify({"message": f"Error calculando resumen: {str(e)}", "success": False}), 500

@app.route('/api/estadisticas/historico', methods=['GET'])
def get_estadisticas_historico():
    """Retorna datos para gráficas (últimos 7 días)"""
    try:
        hoy = datetime.now().date()
        dias = []
        ventas_data = []
        compras_data = []
        
        # Datos para gráfica lineal (Últimos 7 días)
        for i in range(6, -1, -1):
            fecha = hoy - timedelta(days=i)
            inicio = datetime.combine(fecha, datetime.min.time())
            fin = datetime.combine(fecha, datetime.max.time())
            
            # Ventas del día
            ventas = Venta.query.filter(Venta.fecha_creacion.between(inicio, fin)).all()
            total_v = sum(sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles) for v in ventas)
            
            # Compras del día
            compras = Compra.query.filter(Compra.fecha_creacion.between(inicio, fin)).all()
            # Fix: use precio_unitario_tipo_dolares instead of precio_unitario
            total_c = sum(sum(d.precio_unitario_tipo_dolares * d.cantidad for d in c.detalles) for c in compras)
            
            dias.append(fecha.strftime('%d/%m'))
            ventas_data.append(float(total_v))
            compras_data.append(float(total_c))
            
        # Top 5 Productos más vendidos
        from sqlalchemy import func
        # Note: We must handle cases where join returns no rows, resulting in None sums if not grouping correctly,
        # but group_by usually filters out null groups unless outer joined.
        # Here we use inner joins, so we are safe from null products, but sum could be null? No, quantity is non-nullable.
        
        top_productos_query = db.session.query(
            Producto.nombre,
            func.sum(DetalleVenta.cantidad).label('total_vendido')
        ).join(DetalleVenta).group_by(Producto.id).order_by(func.sum(DetalleVenta.cantidad).desc()).limit(5).all()
        
        top_productos = {
            "labels": [p[0] for p in top_productos_query],
            "data": [int(p[1] or 0) for p in top_productos_query] 
        }
        
        # Ventas por Categoría
        ventas_categoria_query = db.session.query(
            Categoria.nombre,
            func.sum(DetalleVenta.cantidad).label('total')
        ).join(Producto, Categoria.id == Producto.id_categoria)\
         .join(DetalleVenta, Producto.id == DetalleVenta.id_producto)\
         .group_by(Categoria.id).all()
         
        ventas_por_categoria = {
            "labels": [c[0] for c in ventas_categoria_query],
            "data": [int(c[1] or 0) for c in ventas_categoria_query]
        }
            
        return jsonify({
            "fechas": dias,
            "ventas": ventas_data,
            "compras": compras_data,
            "top_productos": top_productos,
            "ventas_por_categoria": ventas_por_categoria
        })
        
    except Exception as e:
        print(f"Error en historico: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify({"message": f"Error calculando histórico: {str(e)}", "success": False}), 500


if __name__ == '__main__':
    initialize_database()
    print("\n" + "="*60)
    print("🚀 Servidor Flask corriendo en http://127.0.0.1:5000")
    print("="*60 + "\n")
    app.run(debug=True, port=5000)
