import os
from datetime import datetime
from decimal import Decimal

from flask import Blueprint, current_app, request, send_file

from models import Cotizacion, Negocio, Reembolso, Usuario, Venta, db

reembolsos_bp = Blueprint("reembolsos", __name__, url_prefix="/reembolsos")


@reembolsos_bp.get("/")
def get_reembolsos():
    try:
        reembolsos = Reembolso.query.order_by(Reembolso.fecha.desc()).all()
        return [r.to_dict() for r in reembolsos]
    except Exception as e:
        return {
            "message": f"Error al obtener reembolsos: {str(e)}",
            "success": False,
        }, 500


@reembolsos_bp.post("/")
def create_reembolso():
    data = request.get_json()
    try:
        id_venta = data.get("id_venta")
        id_usuario = data.get("id_usuario")
        monto_dolares = Decimal(str(data.get("monto_dolares")))
        motivo = data.get("motivo")

        venta = db.session.get(Venta, id_venta)
        if not venta:
            return {"message": "Venta no encontrada", "success": False}, 404

        # Usar la tasa histórica de la venta
        tasa_cambio = venta.cotizacion_dolar_bolivares
        if not tasa_cambio or tasa_cambio == 0:
            # Fallback si es una venta antigua sin tasa guardada
            cotizacion_actual = Cotizacion.query.order_by(
                Cotizacion.fecha_hora.desc()
            ).first()
            tasa_cambio = (
                cotizacion_actual.tasa_dolar_bolivares
                if cotizacion_actual
                else Decimal("1.00")
            )

        monto_bolivares = monto_dolares * tasa_cambio

        nuevo_reembolso = Reembolso(
            id_venta=id_venta,
            id_usuario=id_usuario,
            monto_dolares=monto_dolares,
            monto_bolivares=monto_bolivares,
            tasa_cambio=tasa_cambio,
            motivo=motivo,
        )

        db.session.add(nuevo_reembolso)
        db.session.commit()

        return {
            "success": True,
            "message": "Reembolso procesado exitosamente",
            "reembolso": nuevo_reembolso.to_dict(),
        }, 201

    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al procesar reembolso: {str(e)}",
            "success": False,
        }, 500


@reembolsos_bp.delete("/<int:id>")
def delete_reembolso(id: int):
    try:
        reembolso = db.session.get(Reembolso, id)
        if not reembolso:
            return {"message": "Reembolso no encontrado", "success": False}, 404
        db.session.delete(reembolso)
        db.session.commit()
        return {"success": True, "message": "Reembolso eliminado correctamente"}
    except Exception as e:
        db.session.rollback()
        return {
            "message": f"Error al eliminar reembolso: {str(e)}",
            "success": False,
        }, 500


@reembolsos_bp.get("/<int:id>/pdf")
def get_reembolso_pdf(id: int):
    try:
        reembolso = db.session.get(Reembolso, id)
        if not reembolso:
            return {"message": "Reembolso no encontrado", "success": False}, 404

        venta = db.session.get(Venta, reembolso.id_venta)
        usuario = db.session.get(Usuario, reembolso.id_usuario)
        if not venta or not usuario:
            return {
                "message": "Datos asociados al reembolso no encontrados",
                "success": False,
            }, 404
        negocio = Negocio.query.first()

        # Create a simple PDF receipt for the refund
        from reportlab.lib import colors
        from reportlab.lib.enums import TA_CENTER
        from reportlab.lib.pagesizes import letter
        from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
        from reportlab.lib.units import inch
        from reportlab.platypus import (
            Paragraph,
            SimpleDocTemplate,
            Spacer,
            Table,
            TableStyle,
        )

        # Create folder
        reembolsos_dir = os.path.join(current_app.instance_path, "reembolsos_pdf")
        os.makedirs(reembolsos_dir, exist_ok=True)

        filename = f"reembolso_{id}_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pdf"
        filepath = os.path.join(reembolsos_dir, filename)

        doc = SimpleDocTemplate(filepath, pagesize=letter)
        elements = []
        styles = getSampleStyleSheet()

        title_style = ParagraphStyle(
            "Title",
            parent=styles["Heading1"],
            fontSize=20,
            textColor=colors.HexColor("#dc3545"),
            alignment=TA_CENTER,
        )

        elements.append(
            Paragraph(negocio.nombre if negocio else "Negocio", title_style)
        )
        elements.append(
            Paragraph(f"Comprobante de Reembolso #{id}", styles["Heading2"])
        )
        elements.append(Spacer(1, 0.3 * inch))

        data = [
            ["Fecha:", reembolso.fecha.strftime("%d/%m/%Y %H:%M")],
            ["Venta Original:", f"#{reembolso.id_venta}"],
            ["Procesado por:", usuario.nombre if usuario else "N/A"],
            ["Monto (USD):", f"${float(reembolso.monto_dolares):.2f}"],
            ["Monto (Bs):", f"{float(reembolso.monto_bolivares):,.2f} Bs"],
            ["Tasa de Cambio:", f"{float(reembolso.tasa_cambio):.2f} Bs/$"],
            ["Motivo:", reembolso.motivo or "-"],
        ]

        table = Table(data, colWidths=[2 * inch, 4 * inch])
        table.setStyle(
            TableStyle(
                [
                    ("BACKGROUND", (0, 0), (0, -1), colors.HexColor("#f8d7da")),
                    ("FONTNAME", (0, 0), (0, -1), "Helvetica-Bold"),
                    ("GRID", (0, 0), (-1, -1), 0.5, colors.grey),
                    ("PADDING", (0, 0), (-1, -1), 8),
                ]
            )
        )

        elements.append(table)
        elements.append(Spacer(1, 0.5 * inch))
        elements.append(
            Paragraph(
                "Este documento es un comprobante de reembolso.",
                styles["Normal"],
            )
        )

        doc.build(elements)

        return send_file(
            filepath, as_attachment=True, download_name=f"reembolso_{id}.pdf"
        )

    except Exception as e:
        return {"message": f"Error al generar PDF: {str(e)}", "success": False}, 500
