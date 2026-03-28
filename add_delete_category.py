#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Script para agregar la funcionalidad de eliminar categorías al modal de productos.
Este script modifica index.html y app.js de forma segura.
"""

import re
import os

def backup_file(filepath):
    """Crea un backup del archivo antes de modificarlo."""
    backup_path = filepath + '.backup2'
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    with open(backup_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"✅ Backup creado: {backup_path}")
    return content

def modify_html():
    """Modifica el modal de productos para agregar botón de eliminar categoría."""
    filepath = 'index.html'
    
    print(f"\n📄 Modificando {filepath}...")
    content = backup_file(filepath)
    
    # Buscar y reemplazar el select de categoría para agregar botón eliminar
    old_category_html = '''<select id="product-categoria" required onchange="handleCategoriaChange()">
                        <option value="">Seleccione...</option>
                    </select>'''
    
    new_category_html = '''<div style="display: flex; gap: 10px; align-items: center;">
                        <select id="product-categoria" required onchange="handleCategoriaChange()" style="flex: 1;">
                            <option value="">Seleccione...</option>
                        </select>
                        <button type="button" class="btn" id="btn-eliminar-categoria" onclick="eliminarCategoria()" style="padding: 8px 12px; background: #fee2e2; color: #ef4444; display: none;" title="Eliminar esta categoría">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>'''
    
    if old_category_html in content:
        content = content.replace(old_category_html, new_category_html)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"✅ {filepath} modificado correctamente - botón eliminar agregado")
        return True
    elif 'btn-eliminar-categoria' in content:
        print(f"⚠️ El botón de eliminar categoría ya existe en {filepath}")
        return True
    else:
        print(f"⚠️ No se encontró el patrón esperado en {filepath}")
        print("   Intentando patrón alternativo...")
        
        # Patrón alternativo con estilo inline
        old_alt = '''<select id="product-categoria" required onchange="handleCategoriaChange()" style="flex: 1;">'''
        if old_alt in content:
            print("   Ya tiene estructura con flex, el botón puede faltar.")
        return False

def modify_js():
    """Modifica app.js para agregar las funciones de eliminar categoría."""
    filepath = 'app.js'
    
    print(f"\n📄 Modificando {filepath}...")
    content = backup_file(filepath)
    
    # Verificar si ya existe la función
    if 'eliminarCategoria' in content:
        print(f"⚠️ La función eliminarCategoria ya existe en {filepath}")
        return True
    
    # Código JavaScript para agregar
    new_js_code = '''

// Eliminar una categoría existente
async function eliminarCategoria() {
    const select = document.getElementById('product-categoria');
    const categoriaId = select.value;
    
    if (!categoriaId || categoriaId === 'nueva' || categoriaId === '') {
        alert('Selecciona una categoría válida para eliminar');
        return;
    }
    
    // Obtener el nombre de la categoría seleccionada
    const nombreCategoria = select.options[select.selectedIndex].text;
    
    if (!confirm(`¿Estás seguro de que deseas eliminar la categoría "${nombreCategoria}"?\\n\\nNota: No se puede eliminar si tiene productos asociados.`)) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/api/categorias/${categoriaId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Eliminar la opción del select
            select.remove(select.selectedIndex);
            select.value = '';
            
            // Ocultar botón de eliminar
            document.getElementById('btn-eliminar-categoria').style.display = 'none';
            
            alert('Categoría eliminada exitosamente');
        } else {
            alert(data.message || 'Error al eliminar categoría');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión al eliminar categoría');
    }
}
'''
    
    # Agregar el código al final del archivo
    content = content.rstrip() + new_js_code
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✅ {filepath} modificado correctamente - función eliminarCategoria agregada")
    return True

def modify_handleCategoriaChange():
    """Modifica handleCategoriaChange para mostrar/ocultar botón de eliminar."""
    filepath = 'app.js'
    
    print(f"\n📄 Modificando handleCategoriaChange en {filepath}...")
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Buscar si ya tiene la lógica del botón eliminar
    if 'btn-eliminar-categoria' in content:
        print(f"⚠️ La lógica del botón eliminar ya existe en handleCategoriaChange")
        return True
    
    # Buscar y reemplazar la función handleCategoriaChange
    old_function = '''function handleCategoriaChange() {
    const select = document.getElementById('product-categoria');
    const container = document.getElementById('nueva-categoria-container');
    
    if (select.value === 'nueva') {
        container.style.display = 'block';
        select.removeAttribute('required');
    } else {
        container.style.display = 'none';
        select.setAttribute('required', 'required');
    }
}'''
    
    new_function = '''function handleCategoriaChange() {
    const select = document.getElementById('product-categoria');
    const container = document.getElementById('nueva-categoria-container');
    const btnEliminar = document.getElementById('btn-eliminar-categoria');
    
    if (select.value === 'nueva') {
        container.style.display = 'block';
        if (btnEliminar) btnEliminar.style.display = 'none';
        select.removeAttribute('required');
    } else if (select.value && select.value !== '') {
        container.style.display = 'none';
        if (btnEliminar) btnEliminar.style.display = 'flex';
        select.setAttribute('required', 'required');
    } else {
        container.style.display = 'none';
        if (btnEliminar) btnEliminar.style.display = 'none';
        select.setAttribute('required', 'required');
    }
}'''
    
    if old_function in content:
        content = content.replace(old_function, new_function)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"✅ handleCategoriaChange actualizado correctamente")
        return True
    else:
        print(f"⚠️ No se encontró el patrón esperado de handleCategoriaChange")
        print("   Es posible que ya haya sido modificado o tenga un formato diferente.")
        return False

def main():
    print("=" * 60)
    print("  SCRIPT: Agregar Opción de Eliminar Categoría")
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
    
    # Modificar handleCategoriaChange
    if not modify_handleCategoriaChange():
        success = False
    
    # Modificar JS (agregar función eliminar)
    if not modify_js():
        success = False
    
    print("\n" + "=" * 60)
    if success:
        print("✅ ¡Modificaciones completadas exitosamente!")
        print("\nCómo usar:")
        print("1. Abre la aplicación → Productos → Agregar/Editar Producto")
        print("2. Selecciona una categoría existente")
        print("3. Aparecerá un botón rojo con icono de papelera")
        print("4. Haz clic para eliminar la categoría seleccionada")
        print("\nNota: No se pueden eliminar categorías con productos asociados.")
    else:
        print("⚠️ Algunas modificaciones no se pudieron completar.")
        print("   Revisa los mensajes anteriores para más detalles.")
    print("=" * 60)

if __name__ == '__main__':
    main()
