"""
Sistema de upload de imágenes para productos
Maneja la carga y almacenamiento de imágenes de productos
"""

import os
from werkzeug.utils import secure_filename
from flask import current_app

# Configuración
UPLOAD_FOLDER = 'static/productos'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif', 'webp'}

def allowed_file(filename):
    """Verifica si el archivo tiene una extensión permitida"""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def save_product_image(file, producto_codigo):
    """
    Guarda una imagen de producto y retorna la URL
    
    Args:
        file: Archivo de imagen (FileStorage)
        producto_codigo: Código del producto para nombrar el archivo
    
    Returns:
        str: URL relativa de la imagen guardada
    """
    if file and allowed_file(file.filename):
        # Crear directorio si no existe
        upload_path = os.path.join(UPLOAD_FOLDER)
        os.makedirs(upload_path, exist_ok=True)
        
        # Generar nombre seguro
        extension = file.filename.rsplit('.', 1)[1].lower()
        filename = f"{secure_filename(producto_codigo)}.{extension}"
        
        # Guardar archivo
        filepath = os.path.join(upload_path, filename)
        file.save(filepath)
        
        # Retornar URL relativa
        return f"/{UPLOAD_FOLDER}/{filename}"
    
    return None

def delete_product_image(imagen_url):
    """
    Elimina una imagen de producto del sistema de archivos
    
    Args:
        imagen_url: URL de la imagen a eliminar
    """
    if imagen_url and imagen_url.startswith('/static/productos/'):
        filepath = imagen_url[1:]  # Remover el "/" inicial
        if os.path.exists(filepath):
            os.remove(filepath)

def get_default_product_images():
    """
    Retorna URLs de imágenes por defecto para productos comunes
    """
    return {
        'smartphone': 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400',
        'laptop': 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400',
        'tablet': 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400',
        'accesorio': 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
        'default': 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400'
    }
