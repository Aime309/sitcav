from flask import Blueprint, request, send_file

from models.cliente import Cliente
from models.usuario import Usuario
from models.venta import Venta
from pdf_generator import generar_reporte_consultas_pdf

consultas_bp = Blueprint("consultas", __name__, url_prefix="/consultas")


@consultas_bp.get("/ventas")
def consultar_ventas():
    try:
        id_vendedor = request.args.get("id_vendedor")
        id_cliente = request.args.get("id_cliente")
        fecha_desde = request.args.get("fecha_desde")
        fecha_hasta = request.args.get("fecha_hasta")

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
                    v_dict["vendedor"] = {
                        "id": vendedor.id,
                        "nombre": vendedor.nombre,
                    }
            else:
                v_dict["vendedor"] = {"id": None, "nombre": "Sin asignar"}

            # Calcular total
            total = sum(d.precio_unitario_tipo_dolares * d.cantidad for d in v.detalles)
            v_dict["total"] = float(total)

            resultado.append(v_dict)

        return resultado
    except Exception as e:
        return {"success": False, "message": f"Error al consultar: {str(e)}"}, 500


@consultas_bp.get("/ventas/pdf")
def exportar_consultas_pdf():
    try:
        # Reutilizar lógica de filtros
        id_vendedor = request.args.get("id_vendedor")
        id_cliente = request.args.get("id_cliente")
        fecha_desde = request.args.get("fecha_desde")
        fecha_hasta = request.args.get("fecha_hasta")

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
                if vend:
                    vendedor_nombre = vend.nombre

            datos_reporte.append(
                {
                    "id": v.id,
                    "fecha": v.fecha_creacion.strftime("%d/%m/%Y %H:%M"),
                    "cliente": f"{v.cliente.nombre} {v.cliente.apellidos}",
                    "vendedor": vendedor_nombre,
                    "total": float(total),
                }
            )

        pdf_path = generar_reporte_consultas_pdf(datos_reporte, filtros_texto)

        return send_file(
            pdf_path, as_attachment=True, download_name="reporte_consultas.pdf"
        )

    except Exception as e:
        return {"success": False, "message": f"Error al generar PDF: {str(e)}"}, 500
