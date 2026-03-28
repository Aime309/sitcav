"""
Script para migrar ventas antiguas y asignarles la cotización histórica correcta.
Las ventas que tienen cotizacion_dolar_bolivares = 0 serán actualizadas con la tasa
que estaba vigente en la fecha de creación de la venta.
"""
import sqlite3
import os

db_path = os.path.join('instance', 'system_data.db')

conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Obtener todas las cotizaciones ordenadas por fecha
cursor.execute('SELECT id, fecha_hora, tasa_dolar_bolivares FROM cotizaciones ORDER BY fecha_hora ASC')
cotizaciones = cursor.fetchall()
print(f"Cotizaciones encontradas: {len(cotizaciones)}")
for c in cotizaciones:
    print(f"  ID: {c[0]}, Fecha: {c[1]}, Tasa: {c[2]}")

# Obtener ventas con cotización = 0
cursor.execute('SELECT id, fecha_creacion, cotizacion_dolar_bolivares FROM ventas WHERE cotizacion_dolar_bolivares = 0 OR cotizacion_dolar_bolivares IS NULL')
ventas_sin_tasa = cursor.fetchall()
print(f"\nVentas sin tasa histórica: {len(ventas_sin_tasa)}")

if len(cotizaciones) == 0:
    print("No hay cotizaciones registradas. No se puede migrar.")
    conn.close()
    exit()

# Para cada venta sin tasa, encontrar la cotización vigente en esa fecha
for venta in ventas_sin_tasa:
    venta_id = venta[0]
    fecha_venta = venta[1]
    
    # Buscar la cotización más reciente antes o igual a la fecha de la venta
    cursor.execute('''
        SELECT tasa_dolar_bolivares FROM cotizaciones 
        WHERE fecha_hora <= ? 
        ORDER BY fecha_hora DESC 
        LIMIT 1
    ''', (fecha_venta,))
    
    resultado = cursor.fetchone()
    
    if resultado:
        tasa = resultado[0]
    else:
        # Si no hay cotización antes de la venta, usar la primera cotización disponible
        tasa = cotizaciones[0][2]
    
    print(f"Venta ID {venta_id} (Fecha: {fecha_venta}) -> Asignando tasa: {tasa}")
    
    cursor.execute('UPDATE ventas SET cotizacion_dolar_bolivares = ? WHERE id = ?', (tasa, venta_id))

conn.commit()
print(f"\n✓ Migración completada. {len(ventas_sin_tasa)} ventas actualizadas.")

# Verificar
cursor.execute('SELECT id, fecha_creacion, cotizacion_dolar_bolivares FROM ventas')
ventas = cursor.fetchall()
print("\nEstado actual de ventas:")
for v in ventas:
    print(f"  ID: {v[0]}, Fecha: {v[1]}, Tasa: {v[2]}")

conn.close()
