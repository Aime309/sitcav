#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Script de migración para:
1. Agregar columna 'cotizacion_dolar_bolivares' a la tabla 'ventas'.
2. Crear la tabla 'reembolsos'.
"""

import sqlite3
import os

def migrate():
    db_path = os.path.join('instance', 'system_data.db')
    
    if not os.path.exists(db_path):
        print(f"⚠️ Base de datos no encontrada en: {db_path}")
        return False
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    
    print("=" * 60)
    print("  MIGRACIÓN: Ventas (Cotización) y Reembolsos")
    print("=" * 60)
    
    # 1. Agregar cotizacion_dolar_bolivares a ventas
    try:
        cursor.execute("PRAGMA table_info(ventas)")
        columns = [col[1] for col in cursor.fetchall()]
        
        if 'cotizacion_dolar_bolivares' not in columns:
            cursor.execute("ALTER TABLE ventas ADD COLUMN cotizacion_dolar_bolivares NUMERIC(10, 2) DEFAULT 0")
            print("✅ Columna 'cotizacion_dolar_bolivares' agregada a tabla 'ventas'")
            
            # Actualizar registros existentes con un valor por defecto (opcional, ej: 1.0)
            # cursor.execute("UPDATE ventas SET cotizacion_dolar_bolivares = 1.0 WHERE cotizacion_dolar_bolivares = 0")
        else:
            print("ℹ️  La columna 'cotizacion_dolar_bolivares' ya existe en 'ventas'")
    except sqlite3.Error as e:
        print(f"❌ Error al modificar 'ventas': {e}")
        
    # 2. Crear tabla reembolsos
    try:
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
        print("✅ Tabla 'reembolsos' verificada/creada")
    except sqlite3.Error as e:
        print(f"❌ Error al crear tabla 'reembolsos': {e}")
    
    conn.commit()
    conn.close()
    
    print("\n" + "=" * 60)
    print("✅ Migración completada")
    print("=" * 60)
    return True

if __name__ == '__main__':
    migrate()
