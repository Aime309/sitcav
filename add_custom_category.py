#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Script para agregar la funcionalidad de categoría personalizada al modal de productos.
Este script modifica index.html y app.js de forma segura.
"""

import re
import os

def backup_file(filepath):
    """Crea un backup del archivo antes de modificarlo."""
    backup_path = filepath + '.backup'
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    with open(backup_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"✅ Backup creado: {backup_path}")
    return content

def modify_html():
    """Modifica el modal de productos en index.html para agregar la opción de nueva categoría."""
    filepath = 'index.html'
    
    print(f"\n📄 Modificando {filepath}...")
    content = backup_file(filepath)
    
    # Buscar y reemplazar el select de categoría
    old_category_html = '''                <div class="form-group">
                    <label>Categoría</label>
                    <select id="product-categoria" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>'''
    
    new_category_html = '''                <div class="form-group">
                    <label>Categoría</label>
                    <select id="product-categoria" required onchange="handleCategoriaChange()">
                        <option value="">Seleccione...</option>
                    </select>
                    <div id="nueva-categoria-container" style="display: none; margin-top: 10px;">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="text" id="nueva-categoria-nombre" placeholder="Nombre de la nueva categoría" style="flex: 1;">
                            <button type="button" class="btn btn-secondary" onclick="crearNuevaCategoria()" style="padding: 8px 15px;">
                                <i class="fas fa-plus"></i> Crear
                            </button>
                        </div>
                        <small style="color: #6b7280; margin-top: 5px; display: block;">Escribe el nombre y haz clic en "Crear"</small>
                    </div>
                </div>'''
    
    if old_category_html in content:
        content = content.replace(old_category_html, new_category_html)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"✅ {filepath} modificado correctamente")
        return True
    else:
        print(f"⚠️ No se encontró el patrón esperado en {filepath}")
        print("   El archivo puede haber sido modificado previamente o tener un formato diferente.")
        return False

def modify_js():
    """Modifica app.js para agregar las funciones de manejo de categorías."""
    filepath = 'app.js'
    
    print(f"\n📄 Modificando {filepath}...")
    content = backup_file(filepath)
    
    # Código JavaScript para agregar al final del archivo
    new_js_code = '''

// ===== FUNCIONES DE CATEGORÍA PERSONALIZADA =====

// Manejar el cambio en el selector de categoría
function handleCategoriaChange() {
    const select = document.getElementById('product-categoria');
    const container = document.getElementById('nueva-categoria-container');
    
    if (select.value === 'nueva') {
        container.style.display = 'block';
        select.removeAttribute('required');
    } else {
        container.style.display = 'none';
        select.setAttribute('required', 'required');
    }
}

// Crear una nueva categoría
async function crearNuevaCategoria() {
    const nombreInput = document.getElementById('nueva-categoria-nombre');
    const nombre = nombreInput.value.trim();
    
    if (!nombre) {
        alert('Por favor ingresa un nombre para la categoría');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/api/categorias`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: nombre })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            // Agregar la nueva categoría al select
            const select = document.getElementById('product-categoria');
            const newOption = document.createElement('option');
            newOption.value = data.id;
            newOption.textContent = nombre;
            
            // Insertar antes de la opción "Nueva categoría..."
            const nuevaOption = select.querySelector('option[value="nueva"]');
            if (nuevaOption) {
                select.insertBefore(newOption, nuevaOption);
            } else {
                select.appendChild(newOption);
            }
            
            // Seleccionar la nueva categoría
            select.value = data.id;
            
            // Limpiar y ocultar el contenedor
            nombreInput.value = '';
            document.getElementById('nueva-categoria-container').style.display = 'none';
            select.setAttribute('required', 'required');
            
            alert('Categoría creada exitosamente');
        } else {
            const errorData = await response.json();
            alert('Error al crear categoría: ' + (errorData.message || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión al crear categoría');
    }
}
'''
    
    # Verificar si ya existe la función
    if 'handleCategoriaChange' in content:
        print(f"⚠️ Las funciones ya existen en {filepath}")
        return True
    
    # Agregar el código al final del archivo
    content = content.rstrip() + new_js_code
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✅ {filepath} modificado correctamente")
    return True

def modify_openProductModal():
    """Modifica la función openProductModal para agregar la opción 'Nueva categoría...' al select."""
    filepath = 'app.js'
    
    print(f"\n📄 Modificando openProductModal en {filepath}...")
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Buscar el patrón donde se llenan las categorías
    old_pattern = '''select.innerHTML = '<option value="">Seleccione...</option>';
    categories.forEach(cat => {
        select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
    });'''
    
    new_pattern = '''select.innerHTML = '<option value="">Seleccione...</option>';
    categories.forEach(cat => {
        select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
    });
    // Agregar opción para nueva categoría
    select.innerHTML += '<option value="nueva">➕ Nueva categoría...</option>';
    
    // Resetear el contenedor de nueva categoría
    const nuevaCatContainer = document.getElementById('nueva-categoria-container');
    if (nuevaCatContainer) {
        nuevaCatContainer.style.display = 'none';
        document.getElementById('nueva-categoria-nombre').value = '';
    }'''
    
    if old_pattern in content:
        content = content.replace(old_pattern, new_pattern)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"✅ openProductModal modificado correctamente")
        return True
    else:
        print(f"⚠️ No se encontró el patrón esperado en openProductModal")
        return False

def main():
    print("=" * 60)
    print("  SCRIPT: Agregar Categoría Personalizada al Modal de Productos")
    print("=" * 60)
    
    # Verificar que estemos en el directorio correcto
    if not os.path.exists('index.html') or not os.path.exists('app.js'):
        print("\n❌ Error: No se encontraron los archivos index.html y/o app.js")
        print("   Asegúrate de ejecutar este script desde el directorio del proyecto.")
        return
    
    success = True
    
    # Modificar HTML
    if not modify_html():
        success = False
    
    # Modificar JS (agregar funciones)
    if not modify_js():
        success = False
    
    # Modificar openProductModal
    if not modify_openProductModal():
        success = False
    
    print("\n" + "=" * 60)
    if success:
        print("✅ ¡Modificaciones completadas exitosamente!")
        print("\nAhora puedes:")
        print("1. Iniciar el servidor Flask: python app.py")
        print("2. Abrir el frontend en el navegador")
        print("3. Ir a Productos > Agregar Producto")
        print("4. En el campo Categoría, selecciona 'Nueva categoría...'")
        print("5. Escribe el nombre y haz clic en 'Crear'")
    else:
        print("⚠️ Algunas modificaciones no se pudieron completar.")
        print("   Revisa los mensajes anteriores para más detalles.")
        print("   Los backups de los archivos originales terminan en .backup")
    print("=" * 60)

if __name__ == '__main__':
    main()
