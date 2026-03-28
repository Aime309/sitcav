
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
