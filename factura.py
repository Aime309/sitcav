from flask import Blueprint, send_file

from negocio import Negocio
from pdf_generator import generar_factura_pdf
from venta import Venta

factura_bp = Blueprint("factura", __name__, url_prefix="/factura")


@factura_bp.get("/<int:venta_id>")
def generar_factura(venta_id: int):
    try:
        # Obtener la venta con todos sus datos
        venta = Venta.query.get_or_404(venta_id)
        venta_data = venta.to_dict()

        # Obtener datos del negocio
        negocio = Negocio.query.first()
        if not negocio:
            return {
                "success": False,
                "message": "No hay datos del negocio configurados",
            }, 400

        negocio_data = {
            "nombre": negocio.nombre,
            "rif": negocio.rif,
            "telefono": negocio.telefono,
            "direccion": negocio.direccion,
        }

        # Usar la cotización histórica guardada en la venta (no la actual)
        cotizacion_bs = (
            float(venta.cotizacion_dolar_bolivares)
            if venta.cotizacion_dolar_bolivares
            else 35.50
        )

        # Generar el PDF
        pdf_path = generar_factura_pdf(venta_data, negocio_data, cotizacion_bs)

        # Enviar el archivo
        return send_file(
            pdf_path,
            mimetype="application/pdf",
            as_attachment=True,
            download_name=f"factura_{venta_id}.pdf",
        )

    except Exception as e:
        return {"success": False, "message": f"Error al generar factura: {str(e)}"}, 500
