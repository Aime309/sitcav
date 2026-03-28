#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Script de migración segura para agregar columna 'direccion' a las tablas clientes y proveedores.
Este script es seguro y no afectará datos existentes.
"""

import sqlite3
import os

def migrate():
    db_path = os.path.join('instance', 'system_data.db')
    
    if not os.path.exists(db_path):
        print(f"⚠️ Base de datos no encontrada en: {db_path}")
        print("   No te preocupes, las columnas se crearán automáticamente cuando inicies la aplicación.")
        return True
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    print("=" * 60)
    print("  MIGRACIÓN SEGURA: Agregar campo 'direccion'")
    print("=" * 60)
    
    # Verificar y agregar columna direccion a clientes
    try:
        cursor.execute("PRAGMA table_info(clientes)")
        columns = [col[1] for col in cursor.fetchall()]
        
        if 'direccion' not in columns:
            cursor.execute("ALTER TABLE clientes ADD COLUMN direccion VARCHAR(300)")
            print("✅ Columna 'direccion' agregada a tabla 'clientes'")
        else:
            print("ℹ️  La columna 'direccion' ya existe en 'clientes'")
    except sqlite3.Error as e:
        print(f"❌ Error al modificar 'clientes': {e}")
    
    # Verificar y agregar columna direccion a proveedores
    try:
        cursor.execute("PRAGMA table_info(proveedores)")
        columns = [col[1] for col in cursor.fetchall()]
        
        if 'direccion' not in columns:
            cursor.execute("ALTER TABLE proveedores ADD COLUMN direccion VARCHAR(300)")
            print("✅ Columna 'direccion' agregada a tabla 'proveedores'")
        else:
            print("ℹ️  La columna 'direccion' ya existe en 'proveedores'")
    except sqlite3.Error as e:
        print(f"❌ Error al modificar 'proveedores': {e}")
    
    conn.commit()
    conn.close()
    
    print("\n" + "=" * 60)
    print("✅ Migración completada exitosamente")
    print("   Los datos existentes no fueron modificados.")
    print("=" * 60)
    return True

if __name__ == '__main__':
    migrate()
