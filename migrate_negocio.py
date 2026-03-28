#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Script de migración segura para agregar columna 'direccion' a la tabla negocios.
Este script es seguro y no afectará datos existentes.
"""

import sqlite3
import os

def migrate():
    db_path = os.path.join('instance', 'system_data.db')
    
    if not os.path.exists(db_path):
        print(f"⚠️ Base de datos no encontrada en: {db_path}")
        return True
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    print("=" * 60)
    print("  MIGRACIÓN SEGURA: Agregar campo 'direccion' a Negocios")
    print("=" * 60)
    
    # Verificar y agregar columna direccion a negocios
    try:
        cursor.execute("PRAGMA table_info(negocios)")
        columns = [col[1] for col in cursor.fetchall()]
        
        if 'direccion' not in columns:
            cursor.execute("ALTER TABLE negocios ADD COLUMN direccion VARCHAR(300)")
            print("✅ Columna 'direccion' agregada a tabla 'negocios'")
        else:
            print("ℹ️  La columna 'direccion' ya existe en 'negocios'")
    except sqlite3.Error as e:
        print(f"❌ Error al modificar 'negocios': {e}")
    
    conn.commit()
    conn.close()
    
    print("\n" + "=" * 60)
    print("✅ Migración completada exitosamente")
    print("=" * 60)
    return True

if __name__ == '__main__':
    migrate()
