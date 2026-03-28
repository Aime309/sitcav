"""
Script para actualizar URLs de imágenes rotas en la base de datos.
Reemplaza URLs externas que no funcionan por un placeholder local.

Ejecutar con: py fix_broken_images.py
"""

import sqlite3
import os

# Ruta a la base de datos
DB_PATH = os.path.join(os.path.dirname(__file__), 'instance', 'inventario.db')

# URL del placeholder para imágenes rotas
PLACEHOLDER_URL = '/uploads/productos/placeholder.png'

# Dominios externos que suelen causar problemas de CORS
PROBLEMATIC_DOMAINS = [
    'files.gsmchoice.com',
    'gsmchoice.com',
    'i.imgur.com',  # A veces falla
    # Añade más dominios problemáticos aquí
]

def fix_broken_images():
    """Actualiza URLs de imágenes externas problemáticas a placeholder local"""
    
    if not os.path.exists(DB_PATH):
        print(f"❌ Base de datos no encontrada en: {DB_PATH}")
        return
    
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    
    # Obtener todos los productos con imágenes
    cursor.execute("SELECT id, nombre, imagen_url FROM producto WHERE imagen_url IS NOT NULL AND imagen_url != ''")
    productos = cursor.fetchall()
    
    print(f"📦 Analizando {len(productos)} productos con imágenes...")
    print("-" * 50)
    
    fixed_count = 0
    
    for prod_id, nombre, imagen_url in productos:
        # Verificar si la URL es de un dominio problemático
        is_problematic = any(domain in imagen_url for domain in PROBLEMATIC_DOMAINS)
        
        # También verificar si es una URL externa (http/https) vs local
        is_external = imagen_url.startswith('http://') or imagen_url.startswith('https://')
        
        if is_problematic or (is_external and any(domain in imagen_url for domain in PROBLEMATIC_DOMAINS)):
            print(f"🔧 Corrigiendo: {nombre[:30]}...")
            print(f"   URL anterior: {imagen_url[:50]}...")
            
            cursor.execute(
                "UPDATE producto SET imagen_url = ? WHERE id = ?",
                (PLACEHOLDER_URL, prod_id)
            )
            fixed_count += 1
    
    conn.commit()
    conn.close()
    
    print("-" * 50)
    print(f"✅ Se corrigieron {fixed_count} productos")
    print(f"ℹ️  Las imágenes ahora usarán: {PLACEHOLDER_URL}")
    print("\n💡 Para agregar imágenes reales, edita cada producto y sube una imagen local.")

def list_external_images():
    """Lista todos los productos con imágenes externas (para revisión)"""
    
    if not os.path.exists(DB_PATH):
        print(f"❌ Base de datos no encontrada en: {DB_PATH}")
        return
    
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    
    cursor.execute("""
        SELECT id, nombre, imagen_url 
        FROM producto 
        WHERE imagen_url LIKE 'http%'
        ORDER BY nombre
    """)
    productos = cursor.fetchall()
    
    print(f"\n📋 Productos con imágenes externas ({len(productos)} encontrados):")
    print("-" * 70)
    
    for prod_id, nombre, imagen_url in productos:
        print(f"ID {prod_id}: {nombre[:25]:25} | {imagen_url[:40]}...")
    
    conn.close()
    
    return productos

if __name__ == '__main__':
    print("=" * 60)
    print("🖼️  CORRECTOR DE IMÁGENES ROTAS")
    print("=" * 60)
    
    # Primero mostrar las imágenes externas
    external = list_external_images()
    
    if external:
        print("\n" + "=" * 60)
        response = input("¿Desea reemplazar todas las URLs externas por placeholder? (s/n): ")
        
        if response.lower() == 's':
            fix_broken_images()
        else:
            print("❌ Operación cancelada")
    else:
        print("✅ No hay imágenes externas que corregir")
