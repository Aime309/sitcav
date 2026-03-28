"""
Generador de PDFs para Facturas - Sistema de Gestión Administrativo
Usa ReportLab para crear facturas profesionales en PDF
"""

from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import inch
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer, Image
from reportlab.lib.enums import TA_CENTER, TA_RIGHT, TA_LEFT
from datetime import datetime
from decimal import Decimal
import os

def generar_factura_pdf(venta_data, negocio_data, cotizacion_bs):
    """
    Genera un PDF de factura para una venta
    
    Args:
        venta_data: Diccionario con datos de la venta (incluyendo cliente y detalles)
        negocio_data: Diccionario con datos del negocio
        cotizacion_bs: Tasa de cambio bolívares/dólar
    
    Returns:
        str: Ruta al archivo PDF generado
    """
    
    # Crear carpeta de facturas si no existe
    facturas_dir = os.path.join('instance', 'facturas')
    os.makedirs(facturas_dir, exist_ok=True)
    
    # Nombre del archivo
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"factura_{venta_data['id']}_{timestamp}.pdf"
    filepath = os.path.join(facturas_dir, filename)
    
    # Crear el documento PDF
    doc = SimpleDocTemplate(
        filepath,
        pagesize=letter,
        rightMargin=0.5*inch,
        leftMargin=0.5*inch,
        topMargin=0.5*inch,
        bottomMargin=0.5*inch
    )
    
    # Contenedor para los elementos del PDF
    elements = []
    
    # Estilos
    styles = getSampleStyleSheet()
    
    # Estilo personalizado para el título
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=24,
        textColor=colors.HexColor('#007bff'),
        spaceAfter=12,
        alignment=TA_CENTER
    )
    
    # Estilo para subtítulos
    subtitle_style = ParagraphStyle(
        'Subtitle',
        parent=styles['Normal'],
        fontSize=10,
        textColor=colors.grey,
        alignment=TA_CENTER,
        spaceAfter=20
    )
    
    # Estilo para secciones
    section_style = ParagraphStyle(
        'Section',
        parent=styles['Heading2'],
        fontSize=12,
        textColor=colors.HexColor('#2c5896'),
        spaceAfter=6,
        spaceBefore=12
    )
    
    # === ENCABEZADO ===
    elements.append(Paragraph(negocio_data['nombre'], title_style))
    direccion_negocio = negocio_data.get('direccion', 'Dirección no registrada')
    elements.append(Paragraph(f"RIF: {negocio_data['rif']} | Tel: {negocio_data['telefono']}", subtitle_style))
    elements.append(Paragraph(f"{direccion_negocio}", subtitle_style))
    
    # Línea separadora
    elements.append(Spacer(1, 0.2*inch))
    
    # === INFORMACIÓN DE LA FACTURA ===
    fecha_venta = datetime.strptime(venta_data['fecha_creacion'], '%Y-%m-%d %H:%M:%S.%f')
    direccion_cliente = venta_data['cliente'].get('direccion', 'Dirección no registrada')
    
    info_factura = [
        ['FACTURA', f"#{venta_data['id']:06d}"],
        ['FECHA', fecha_venta.strftime('%d/%m/%Y %H:%M')],
        ['CLIENTE', f"{venta_data['cliente']['nombre']} {venta_data['cliente']['apellidos']}"],
        ['CÉDULA', venta_data['cliente']['cedula']],
        ['TELÉFONO', venta_data['cliente']['telefono'] or 'N/A'],
        ['DIRECCIÓN', direccion_cliente]
    ]
    
    table_info = Table(info_factura, colWidths=[2*inch, 4*inch])
    table_info.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (0, -1), colors.HexColor('#f0f0f0')),
        ('TEXTCOLOR', (0, 0), (0, -1), colors.HexColor('#2c5896')),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
        ('FONTNAME', (0, 0), (0, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
        ('TOPPADDING', (0, 0), (-1, -1), 8),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    
    elements.append(table_info)
    elements.append(Spacer(1, 0.3*inch))
    
    # === DETALLES DE LA VENTA ===
    elements.append(Paragraph("DETALLE DE PRODUCTOS", section_style))
    
    # Encabezados de la tabla
    data_productos = [['Cant.', 'Producto', 'Precio Unit. ($)', 'Subtotal ($)']]
    
    total_usd = Decimal('0')
    
    # Agregar cada producto
    for detalle in venta_data['detalles']:
        cantidad = detalle['cantidad']
        precio_unit = Decimal(str(detalle['precio_unitario_tipo_dolares']))
        subtotal = precio_unit * cantidad
        total_usd += subtotal
        
        # Prepare product name with ID and IMEI
        producto_nombre = detalle['producto']['nombre']
        producto_id = detalle['producto']['id']
        producto_imei = detalle['producto'].get('imei')
        
        display_name = f"[{producto_id}] {producto_nombre}"
        if producto_imei:
            display_name += f"\nIMEI: {producto_imei}"
        
        data_productos.append([
            str(cantidad),
            display_name,
            f"$ {float(precio_unit):.2f}",
            f"$ {float(subtotal):.2f}"
        ])
    
    # Crear tabla de productos
    # Adjusted widths to give more space to numbers: [0.8, 3.1, 1.8, 1.8] = 7.5 inches (full width)
    table_productos = Table(data_productos, colWidths=[0.8*inch, 3.1*inch, 1.8*inch, 1.8*inch])
    table_productos.setStyle(TableStyle([
        # Encabezado
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#007bff')),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 11),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 10),
        
        # Cuerpo
        ('ALIGN', (0, 1), (0, -1), 'CENTER'),
        ('ALIGN', (2, 1), (-1, -1), 'RIGHT'),
        ('FONTNAME', (0, 1), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 1), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 8),
        ('TOPPADDING', (0, 1), (-1, -1), 8),
        
        # Bordes
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
        ('LINEBELOW', (0, 0), (-1, 0), 2, colors.HexColor('#007bff')),
    ]))
    
    elements.append(table_productos)
    elements.append(Spacer(1, 0.2*inch))
    
    # === TOTALES ===
    # Usar la cotización histórica si está disponible en venta_data, sino usar la actual pasada como argumento
    tasa_cambio = Decimal(str(venta_data.get('cotizacion_dolar_bolivares', 0)))
    if tasa_cambio == 0:
        tasa_cambio = Decimal(str(cotizacion_bs))
        
    total_bs = total_usd * tasa_cambio
    
    data_totales = [
        ['', '', 'TOTAL (USD):', f"$ {float(total_usd):.2f}"],
        ['', '', f'Cotización: {float(tasa_cambio)} Bs/$', ''],
        ['', '', 'TOTAL (Bs):', f"{float(total_bs):,.2f} Bs"]
    ]
    
    table_totales = Table(data_totales, colWidths=[0.8*inch, 3.1*inch, 1.8*inch, 1.8*inch])
    table_totales.setStyle(TableStyle([
        ('ALIGN', (2, 0), (-1, -1), 'RIGHT'),
        ('FONTNAME', (2, 0), (2, 0), 'Helvetica-Bold'),
        ('FONTNAME', (2, 2), (2, 2), 'Helvetica-Bold'),
        ('FONTSIZE', (2, 0), (-1, 0), 12),
        ('FONTSIZE', (2, 2), (-1, 2), 14),
        ('TEXTCOLOR', (2, 0), (-1, 0), colors.HexColor('#007bff')),
        ('TEXTCOLOR', (2, 2), (-1, 2), colors.HexColor('#28a745')),
        ('LINEABOVE', (2, 0), (-1, 0), 1, colors.grey),
        ('LINEABOVE', (2, 2), (-1, 2), 2, colors.HexColor('#28a745')),
        ('TOPPADDING', (2, 0), (-1, -1), 8),
        ('BOTTOMPADDING', (2, 0), (-1, -1), 8),
    ]))
    
    elements.append(table_totales)
    
    # === PIE DE PÁGINA ===
    elements.append(Spacer(1, 0.5*inch))
    
    footer_style = ParagraphStyle(
        'Footer',
        parent=styles['Normal'],
        fontSize=8,
        textColor=colors.grey,
        alignment=TA_CENTER
    )
    
    elements.append(Paragraph("Gracias por su preferencia", footer_style))
    elements.append(Paragraph(f"Factura generada el {datetime.now().strftime('%d/%m/%Y %H:%M')}", footer_style))
    
    # Construir el PDF
    doc.build(elements)
    
    return filepath


def generar_reporte_ventas_pdf(ventas_data, fecha_desde=None, fecha_hasta=None):
    """
    Genera un PDF con reporte de ventas
    
    Args:
        ventas_data: Lista de ventas con sus datos
        fecha_desde: Fecha de inicio del reporte (opcional)
        fecha_hasta: Fecha de fin del reporte (opcional)
    
    Returns:
        str: Ruta al archivo PDF generado
    """
    
    # Crear carpeta de reportes si no existe
    reportes_dir = os.path.join('instance', 'reportes')
    os.makedirs(reportes_dir, exist_ok=True)
    
    # Nombre del archivo
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"reporte_ventas_{timestamp}.pdf"
    filepath = os.path.join(reportes_dir, filename)
    
    # Crear el documento PDF
    doc = SimpleDocTemplate(
        filepath,
        pagesize=letter,
        rightMargin=0.5*inch,
        leftMargin=0.5*inch,
        topMargin=0.5*inch,
        bottomMargin=0.5*inch
    )
    
    elements = []
    styles = getSampleStyleSheet()
    
    # Título
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=20,
        textColor=colors.HexColor('#007bff'),
        spaceAfter=12,
        alignment=TA_CENTER
    )
    
    elements.append(Paragraph("REPORTE DE VENTAS", title_style))
    
    # Período
    if fecha_desde and fecha_hasta:
        periodo = f"Período: {fecha_desde} - {fecha_hasta}"
    else:
        periodo = "Período: Todas las ventas"
    
    elements.append(Paragraph(periodo, styles['Normal']))
    elements.append(Spacer(1, 0.3*inch))
    
    # Tabla de ventas
    data_ventas = [['ID', 'Fecha', 'Cliente', 'Total ($)']]
    
    total_general = Decimal('0')
    
    for venta in ventas_data:
        total_venta = Decimal('0')
        for detalle in venta['detalles']:
            total_venta += Decimal(str(detalle['precio_unitario_tipo_dolares'])) * detalle['cantidad']
        
        total_general += total_venta
        
        fecha = datetime.strptime(venta['fecha_creacion'], '%Y-%m-%d %H:%M:%S.%f')
        
        data_ventas.append([
            f"#{venta['id']:06d}",
            fecha.strftime('%d/%m/%Y'),
            f"{venta['cliente']['nombre']} {venta['cliente']['apellidos']}",
            f"$ {float(total_venta):.2f}"
        ])
    
    # Agregar total
    data_ventas.append(['', '', 'TOTAL:', f"$ {float(total_general):.2f}"])
    
    table_ventas = Table(data_ventas, colWidths=[1*inch, 1.5*inch, 3*inch, 1.5*inch])
    table_ventas.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#007bff')),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 11),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 10),
        
        ('ALIGN', (0, 1), (0, -1), 'CENTER'),
        ('ALIGN', (3, 1), (3, -1), 'RIGHT'),
        ('FONTNAME', (0, 1), (-1, -2), 'Helvetica'),
        ('FONTSIZE', (0, 1), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 8),
        ('TOPPADDING', (0, 1), (-1, -1), 8),
        
        ('GRID', (0, 0), (-1, -2), 0.5, colors.grey),
        ('LINEABOVE', (2, -1), (-1, -1), 2, colors.HexColor('#28a745')),
        ('FONTNAME', (2, -1), (-1, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (2, -1), (-1, -1), 12),
        ('TEXTCOLOR', (2, -1), (-1, -1), colors.HexColor('#28a745')),
    ]))
    
    elements.append(table_ventas)
    
    # Estadísticas
    elements.append(Spacer(1, 0.3*inch))
    elements.append(Paragraph(f"Total de ventas: {len(ventas_data)}", styles['Normal']))
    elements.append(Paragraph(f"Generado: {datetime.now().strftime('%d/%m/%Y %H:%M')}", styles['Normal']))
    
    doc.build(elements)
    
    return filepath


def generar_factura_compra_pdf(compra_data, negocio_data):
    """
    Genera un PDF de factura/orden para una compra
    
    Args:
        compra_data: Diccionario con datos de la compra (incluyendo proveedor y detalles)
        negocio_data: Diccionario con datos del negocio
    
    Returns:
        str: Ruta al archivo PDF generado
    """
    
    # Crear carpeta de facturas si no existe
    facturas_dir = os.path.join('instance', 'facturas_compras')
    os.makedirs(facturas_dir, exist_ok=True)
    
    # Nombre del archivo
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"compra_{compra_data['id']}_{timestamp}.pdf"
    filepath = os.path.join(facturas_dir, filename)
    
    # Crear el documento PDF
    doc = SimpleDocTemplate(
        filepath,
        pagesize=letter,
        rightMargin=0.5*inch,
        leftMargin=0.5*inch,
        topMargin=0.5*inch,
        bottomMargin=0.5*inch
    )
    
    elements = []
    styles = getSampleStyleSheet()
    
    # Estilos personalizados
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=24,
        textColor=colors.HexColor('#dc3545'),  # Rojo para diferenciar de ventas
        spaceAfter=12,
        alignment=TA_CENTER
    )
    
    subtitle_style = ParagraphStyle(
        'Subtitle',
        parent=styles['Normal'],
        fontSize=10,
        textColor=colors.grey,
        alignment=TA_CENTER,
        spaceAfter=20
    )
    
    section_style = ParagraphStyle(
        'Section',
        parent=styles['Heading2'],
        fontSize=12,
        textColor=colors.HexColor('#dc3545'),
        spaceAfter=6,
        spaceBefore=12
    )
    
    # === ENCABEZADO ===
    elements.append(Paragraph(negocio_data['nombre'], title_style))
    direccion_negocio = negocio_data.get('direccion', 'Dirección no registrada')
    elements.append(Paragraph(f"RIF: {negocio_data['rif']} | Tel: {negocio_data['telefono']}", subtitle_style))
    elements.append(Paragraph(f"{direccion_negocio}", subtitle_style))
    elements.append(Spacer(1, 0.2*inch))
    
    # === INFORMACIÓN DE LA COMPRA ===
    fecha_compra = datetime.strptime(compra_data['fecha_creacion'], '%Y-%m-%d %H:%M:%S')
    
    # Obtener datos del proveedor (simulado si no vienen completos, aunque deberían)
    # En el modelo actual, compra_data tiene id_proveedor. 
    # Idealmente el endpoint debe inyectar los datos del proveedor o los buscamos.
    # Asumiremos que compra_data ya viene enriquecido con 'proveedor' o lo manejamos en app.py
    # Para este generador, esperamos que 'proveedor' esté en compra_data
    
    proveedor_nombre = compra_data.get('proveedor', {}).get('nombre', 'N/A')
    proveedor_rif = compra_data.get('proveedor', {}).get('rif', 'N/A')
    proveedor_tel = compra_data.get('proveedor', {}).get('telefono', 'N/A')
    proveedor_direccion = compra_data.get('proveedor', {}).get('direccion', 'Dirección no registrada')

    info_factura = [
        ['ORDEN DE COMPRA', f"#{compra_data['id']:06d}"],
        ['FECHA', fecha_compra.strftime('%d/%m/%Y %H:%M')],
        ['PROVEEDOR', proveedor_nombre],
        ['RIF PROVEEDOR', proveedor_rif],
        ['TELÉFONO', proveedor_tel],
        ['DIRECCIÓN', proveedor_direccion]
    ]
    
    table_info = Table(info_factura, colWidths=[2*inch, 4*inch])
    table_info.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (0, -1), colors.HexColor('#f8d7da')), # Fondo rojizo claro
        ('TEXTCOLOR', (0, 0), (0, -1), colors.HexColor('#721c24')),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
        ('FONTNAME', (0, 0), (0, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
        ('TOPPADDING', (0, 0), (-1, -1), 8),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    
    elements.append(table_info)
    elements.append(Spacer(1, 0.3*inch))
    
    # === DETALLES DE LA COMPRA ===
    elements.append(Paragraph("DETALLE DE PRODUCTOS ADQUIRIDOS", section_style))
    
    data_productos = [['Cant.', 'Producto', 'Costo Unit. ($)', 'Subtotal ($)']]
    
    total_usd = Decimal('0')
    
    for detalle in compra_data['detalles']:
        cantidad = detalle['cantidad']
        precio_unit = Decimal(str(detalle['precio_unitario']))
        subtotal = precio_unit * cantidad
        total_usd += subtotal
        
        # Nombre del producto: si no viene en detalle, usar placeholder
        producto_nombre = detalle.get('producto', {}).get('nombre', 'Producto ID ' + str(detalle['id_producto']))
        
        data_productos.append([
            str(cantidad),
            producto_nombre,
            f"$ {float(precio_unit):.2f}",
            f"$ {float(subtotal):.2f}"
        ])
    
    table_productos = Table(data_productos, colWidths=[0.8*inch, 3.1*inch, 1.8*inch, 1.8*inch])
    table_productos.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#dc3545')),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 11),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 10),
        
        ('ALIGN', (0, 1), (0, -1), 'CENTER'),
        ('ALIGN', (2, 1), (-1, -1), 'RIGHT'),
        ('FONTNAME', (0, 1), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 1), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 8),
        ('TOPPADDING', (0, 1), (-1, -1), 8),
        
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
        ('LINEBELOW', (0, 0), (-1, 0), 2, colors.HexColor('#dc3545')),
    ]))
    
    elements.append(table_productos)
    elements.append(Spacer(1, 0.2*inch))
    
    # === TOTALES ===
    cotizacion = float(compra_data['cotizacion_dolar_bolivares'])
    total_bs = total_usd * Decimal(str(cotizacion))
    
    data_totales = [
        ['', '', 'TOTAL (USD):', f"$ {float(total_usd):.2f}"],
        ['', '', f'Tasa: {cotizacion} Bs/$', ''],
        ['', '', 'TOTAL (Bs):', f"{float(total_bs):,.2f} Bs"]
    ]
    
    table_totales = Table(data_totales, colWidths=[0.8*inch, 3.1*inch, 1.8*inch, 1.8*inch])
    table_totales.setStyle(TableStyle([
        ('ALIGN', (2, 0), (-1, -1), 'RIGHT'),
        ('FONTNAME', (2, 0), (2, 0), 'Helvetica-Bold'),
        ('FONTNAME', (2, 2), (2, 2), 'Helvetica-Bold'),
        ('FONTSIZE', (2, 0), (-1, 0), 12),
        ('FONTSIZE', (2, 2), (-1, 2), 14),
        ('TEXTCOLOR', (2, 0), (-1, 0), colors.HexColor('#dc3545')),
        ('TEXTCOLOR', (2, 2), (-1, 2), colors.black),
        ('LINEABOVE', (2, 0), (-1, 0), 1, colors.grey),
        ('LINEABOVE', (2, 2), (-1, 2), 2, colors.black),
        ('TOPPADDING', (2, 0), (-1, -1), 8),
        ('BOTTOMPADDING', (2, 0), (-1, -1), 8),
    ]))
    
    elements.append(table_totales)
    
    # === PIE DE PÁGINA ===
    elements.append(Spacer(1, 0.5*inch))
    
    footer_style = ParagraphStyle(
        'Footer',
        parent=styles['Normal'],
        fontSize=8,
        textColor=colors.grey,
        alignment=TA_CENTER
    )
    
    elements.append(Paragraph("Comprobante de Compra - Uso Interno", footer_style))
    elements.append(Paragraph(f"Generado el {datetime.now().strftime('%d/%m/%Y %H:%M')}", footer_style))
    
    doc.build(elements)
    
    return filepath

def generar_apartado_pdf(apartado_data, negocio_data):
    """
    Genera un PDF de comprobante para un apartado
    
    Args:
        apartado_data: Diccionario con datos del apartado
        negocio_data: Diccionario con datos del negocio
    
    Returns:
        str: Ruta al archivo PDF generado
    """
    
    # Crear carpeta de apartados si no existe
    apartados_dir = os.path.join('instance', 'apartados_pdf')
    os.makedirs(apartados_dir, exist_ok=True)
    
    # Nombre del archivo
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"apartado_{apartado_data['id']}_{timestamp}.pdf"
    filepath = os.path.join(apartados_dir, filename)
    
    # Crear el documento PDF
    doc = SimpleDocTemplate(
        filepath,
        pagesize=letter,
        rightMargin=0.5*inch,
        leftMargin=0.5*inch,
        topMargin=0.5*inch,
        bottomMargin=0.5*inch
    )
    
    elements = []
    styles = getSampleStyleSheet()
    
    # Estilos personalizados
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=24,
        textColor=colors.HexColor('#ffc107'),  # Amarillo/Naranja para apartados
        spaceAfter=12,
        alignment=TA_CENTER
    )
    
    subtitle_style = ParagraphStyle(
        'Subtitle',
        parent=styles['Normal'],
        fontSize=10,
        textColor=colors.grey,
        alignment=TA_CENTER,
        spaceAfter=20
    )
    
    section_style = ParagraphStyle(
        'Section',
        parent=styles['Heading2'],
        fontSize=12,
        textColor=colors.HexColor('#ffc107'),
        spaceAfter=6,
        spaceBefore=12
    )
    
    # === ENCABEZADO ===
    elements.append(Paragraph(negocio_data['nombre'], title_style))
    elements.append(Paragraph(f"RIF: {negocio_data['rif']} | Tel: {negocio_data['telefono']}", subtitle_style))
    elements.append(Spacer(1, 0.2*inch))
    
    # === INFORMACIÓN DEL APARTADO ===
    fecha_creacion = datetime.strptime(apartado_data['fecha_creacion'], '%Y-%m-%d %H:%M:%S')
    fecha_limite = datetime.strptime(apartado_data['fecha_limite'], '%Y-%m-%d %H:%M:%S')
    
    cliente_nombre = "N/A"
    cliente_cedula = "N/A"
    cliente_telefono = "N/A"
    
    if apartado_data.get('cliente'):
        cliente_nombre = f"{apartado_data['cliente']['nombre']} {apartado_data['cliente']['apellidos']}"
        cliente_cedula = apartado_data['cliente']['cedula']
        cliente_telefono = apartado_data['cliente']['telefono'] or 'N/A'

    info_factura = [
        ['COMPROBANTE DE APARTADO', f"#{apartado_data['id']:06d}"],
        ['FECHA CREACIÓN', fecha_creacion.strftime('%d/%m/%Y')],
        ['FECHA LÍMITE', fecha_limite.strftime('%d/%m/%Y')],
        ['ESTADO', apartado_data['estado'].upper()],
        ['CLIENTE', cliente_nombre],
        ['CÉDULA', cliente_cedula],
        ['TELÉFONO', cliente_telefono]
    ]
    
    table_info = Table(info_factura, colWidths=[2*inch, 4*inch])
    table_info.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (0, -1), colors.HexColor('#fff3cd')), # Fondo amarillo claro
        ('TEXTCOLOR', (0, 0), (0, -1), colors.HexColor('#856404')),
        ('ALIGN', (0, 0), (-1, -1), 'LEFT'),
        ('FONTNAME', (0, 0), (0, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 8),
        ('TOPPADDING', (0, 0), (-1, -1), 8),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    
    elements.append(table_info)
    elements.append(Spacer(1, 0.3*inch))
    
    # === DETALLES DEL APARTADO ===
    elements.append(Paragraph("PRODUCTOS APARTADOS", section_style))
    
    data_productos = [['Cant.', 'Producto', 'Precio Unit. ($)', 'Subtotal ($)']]
    
    total_usd = Decimal('0')
    
    for detalle in apartado_data['detalles']:
        cantidad = detalle['cantidad']
        precio_unit = Decimal(str(detalle['precio_unitario']))
        subtotal = precio_unit * cantidad
        total_usd += subtotal
        
        producto_nombre = detalle.get('producto', {}).get('nombre', 'Producto ID ' + str(detalle['id_producto']))
        producto_imei = detalle.get('producto', {}).get('imei')
        display_name = f"[{detalle['id_producto']}] {producto_nombre}"
        if producto_imei:
            display_name += f"\nIMEI: {producto_imei}"
        
        data_productos.append([
            str(cantidad),
            display_name,
            f"$ {float(precio_unit):.2f}",
            f"$ {float(subtotal):.2f}"
        ])
    
    table_productos = Table(data_productos, colWidths=[0.8*inch, 3.1*inch, 1.8*inch, 1.8*inch])
    table_productos.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#ffc107')),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.black),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 11),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 10),
        
        ('ALIGN', (0, 1), (0, -1), 'CENTER'),
        ('ALIGN', (2, 1), (-1, -1), 'RIGHT'),
        ('FONTNAME', (0, 1), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 1), (-1, -1), 10),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 8),
        ('TOPPADDING', (0, 1), (-1, -1), 8),
        
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
        ('LINEBELOW', (0, 0), (-1, 0), 2, colors.HexColor('#ffc107')),
    ]))
    
    elements.append(table_productos)
    elements.append(Spacer(1, 0.2*inch))
    
    # === PAGOS Y TOTALES ===
    elements.append(Paragraph("HISTORIAL DE PAGOS", section_style))
    
    data_pagos = [['Fecha', 'Monto ($)', 'Observación']]
    total_pagado = Decimal('0')
    
    if apartado_data.get('pagos'):
        for pago in apartado_data['pagos']:
            fecha_pago = datetime.strptime(pago['fecha_pago'], '%Y-%m-%d %H:%M:%S')
            monto = Decimal(str(pago['monto']))
            total_pagado += monto
            
            data_pagos.append([
                fecha_pago.strftime('%d/%m/%Y'),
                f"$ {float(monto):.2f}",
                pago['observacion'] or '-'
            ])
    else:
        data_pagos.append(['-', '$ 0.00', 'Sin pagos registrados'])

    table_pagos = Table(data_pagos, colWidths=[1.5*inch, 1.5*inch, 3.5*inch])
    table_pagos.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('GRID', (0, 0), (-1, -1), 0.5, colors.grey),
    ]))
    
    elements.append(table_pagos)
    elements.append(Spacer(1, 0.2*inch))

    # === RESUMEN FINAL ===
    pendiente = total_usd - total_pagado
    
    data_totales = [
        ['TOTAL APARTADO:', f"$ {float(total_usd):.2f}"],
        ['TOTAL PAGADO:', f"$ {float(total_pagado):.2f}"],
        ['PENDIENTE:', f"$ {float(pendiente):.2f}"]
    ]
    
    table_totales = Table(data_totales, colWidths=[5.5*inch, 2.0*inch])
    table_totales.setStyle(TableStyle([
        ('ALIGN', (0, 0), (-1, -1), 'RIGHT'),
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, -1), 12),
        ('TEXTCOLOR', (0, 2), (-1, 2), colors.HexColor('#dc3545') if pendiente > 0 else colors.HexColor('#28a745')),
        ('LINEABOVE', (0, 0), (-1, -1), 1, colors.grey),
    ]))
    
    elements.append(table_totales)
    
    # === PIE DE PÁGINA ===
    elements.append(Spacer(1, 0.5*inch))
    
    footer_style = ParagraphStyle(
        'Footer',
        parent=styles['Normal'],
        fontSize=8,
        textColor=colors.grey,
        alignment=TA_CENTER
    )
    
    elements.append(Paragraph("Comprobante de Apartado", footer_style))
    elements.append(Paragraph(f"Generado el {datetime.now().strftime('%d/%m/%Y %H:%M')}", footer_style))
    
    doc.build(elements)
    
    return filepath

def generar_reporte_consultas_pdf(ventas_data, filtros_texto):
    """
    Genera un PDF con reporte de consultas de ventas
    
    Args:
        ventas_data: Lista de diccionarios con datos de ventas
        filtros_texto: Lista de strings describiendo los filtros aplicados
    
    Returns:
        str: Ruta al archivo PDF generado
    """
    
    # Crear carpeta de reportes si no existe
    reportes_dir = os.path.join('instance', 'reportes')
    os.makedirs(reportes_dir, exist_ok=True)
    
    # Nombre del archivo
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"consulta_ventas_{timestamp}.pdf"
    filepath = os.path.join(reportes_dir, filename)
    
    # Crear el documento PDF
    doc = SimpleDocTemplate(
        filepath,
        pagesize=letter,
        rightMargin=0.5*inch,
        leftMargin=0.5*inch,
        topMargin=0.5*inch,
        bottomMargin=0.5*inch
    )
    
    elements = []
    styles = getSampleStyleSheet()
    
    # Título
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=20,
        textColor=colors.HexColor('#6f42c1'),  # Morado para consultas
        spaceAfter=12,
        alignment=TA_CENTER
    )
    
    elements.append(Paragraph("REPORTE DE VENTAS POR VENDEDOR", title_style))
    
    # Filtros aplicados
    if filtros_texto:
        elements.append(Paragraph("Filtros aplicados:", styles['Heading4']))
        for filtro in filtros_texto:
            elements.append(Paragraph(f"• {filtro}", styles['Normal']))
    else:
        elements.append(Paragraph("Sin filtros (Todas las ventas)", styles['Normal']))
        
    elements.append(Spacer(1, 0.3*inch))
    
    # Tabla de ventas
    data_ventas = [['ID', 'Fecha', 'Vendedor', 'Cliente', 'Total ($)']]
    
    total_general = Decimal('0')
    
    for venta in ventas_data:
        total_general += Decimal(str(venta['total']))
        
        data_ventas.append([
            f"#{venta['id']:06d}",
            venta['fecha'],
            venta['vendedor'],
            venta['cliente'],
            f"$ {venta['total']:.2f}"
        ])
    
    # Agregar total
    data_ventas.append(['', '', '', 'TOTAL:', f"$ {float(total_general):.2f}"])
    
    table_ventas = Table(data_ventas, colWidths=[0.8*inch, 1.2*inch, 1.5*inch, 1.5*inch, 1.5*inch])
    table_ventas.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor('#6f42c1')),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, 0), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 10),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 10),
        
        ('ALIGN', (0, 1), (0, -1), 'CENTER'),
        ('ALIGN', (4, 1), (4, -1), 'RIGHT'),
        ('FONTNAME', (0, 1), (-1, -2), 'Helvetica'),
        ('FONTSIZE', (0, 1), (-1, -1), 9),
        ('BOTTOMPADDING', (0, 1), (-1, -1), 8),
        ('TOPPADDING', (0, 1), (-1, -1), 8),
        
        ('GRID', (0, 0), (-1, -2), 0.5, colors.grey),
        ('LINEABOVE', (3, -1), (-1, -1), 2, colors.HexColor('#6f42c1')),
        ('FONTNAME', (3, -1), (-1, -1), 'Helvetica-Bold'),
        ('FONTSIZE', (3, -1), (-1, -1), 11),
        ('TEXTCOLOR', (3, -1), (-1, -1), colors.HexColor('#6f42c1')),
    ]))
    
    elements.append(table_ventas)
    
    # Pie de página
    elements.append(Spacer(1, 0.5*inch))
    elements.append(Paragraph(f"Generado: {datetime.now().strftime('%d/%m/%Y %H:%M')}", styles['Normal']))
    
    doc.build(elements)
    
    return filepath
