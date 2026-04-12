<?php

declare(strict_types=1);

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;

final class PdfService
{
  public function streamSale(array $sale, array $business): void
  {
    $rows = '';
    $totalUsd = 0.0;

    foreach ($sale['detalles'] as $detail) {
      $quantity = (float) ($detail['cantidad'] ?? 0);
      $unitPrice = (float) ($detail['precio_unitario_tipo_dolares'] ?? $detail['precio_unitario'] ?? 0);
      $subtotal = $quantity * $unitPrice;
      $totalUsd += $subtotal;

      $productName = $detail['producto']['nombre']
        ?? $detail['producto_nombre']
        ?? ('Producto #' . ($detail['id_producto'] ?? 'N/A'));
      $productId = $detail['producto']['id'] ?? $detail['id_producto'] ?? null;
      $imei = $detail['producto']['imei'] ?? null;
      $productLabel = $productId ? "[{$productId}] {$productName}" : $productName;

      if ($imei) {
        $productLabel .= '<br><small>IMEI: ' . $this->escape($imei) . '</small>';
      }

      $rows .= sprintf(
        '<tr><td class="center">%s</td><td>%s</td><td class="right">%s</td><td class="right">%s</td></tr>',
        $this->formatNumber($quantity),
        $productLabel,
        $this->money($unitPrice),
        $this->money($subtotal),
      );
    }

    $totalUsd = (float) ($sale['total'] ?? $totalUsd);
    $exchangeRate = (float) ($sale['cotizacion_dolar_bolivares'] ?? 1);
    $totalBs = $totalUsd * $exchangeRate;
    $client = $sale['cliente'] ?? [];

    $content = '
      <h1>FACTURA</h1>
      <table class="meta">
        <tr><th>Factura</th><td>#' . str_pad((string) $sale['id'], 6, '0', STR_PAD_LEFT) . '</td></tr>
        <tr><th>Fecha</th><td>' . $this->escape($this->dateTime($sale['fecha_creacion'] ?? null)) . '</td></tr>
        <tr><th>Cliente</th><td>' . $this->escape(trim(($client['nombre'] ?? '') . ' ' . ($client['apellidos'] ?? ''))) . '</td></tr>
        <tr><th>Cédula</th><td>' . $this->escape((string) ($client['cedula'] ?? 'N/A')) . '</td></tr>
        <tr><th>Teléfono</th><td>' . $this->escape((string) ($client['telefono'] ?? 'N/A')) . '</td></tr>
        <tr><th>Dirección</th><td>' . $this->escape((string) ($client['direccion'] ?? 'Dirección no registrada')) . '</td></tr>
      </table>
      <h2>Detalle de productos</h2>
      <table class="lines">
        <thead>
          <tr><th>Cant.</th><th>Producto</th><th>Precio Unit. ($)</th><th>Subtotal ($)</th></tr>
        </thead>
        <tbody>' . $rows . '</tbody>
      </table>
      <table class="totals">
        <tr><th>Total (USD)</th><td>' . $this->money($totalUsd) . '</td></tr>
        <tr><th>Cotización</th><td>' . $this->escape(number_format($exchangeRate, 2) . ' Bs/$') . '</td></tr>
        <tr><th>Total (Bs)</th><td>' . $this->escape(number_format($totalBs, 2) . ' Bs') . '</td></tr>
      </table>
      <p class="footer">Gracias por su preferencia.</p>
    ';

    $this->streamDocument("factura_{$sale['id']}.pdf", $this->layout($business, $content, '#2563eb'));
  }

  public function streamPurchase(array $purchase, array $business): void
  {
    $rows = '';
    $totalUsd = 0.0;

    foreach ($purchase['detalles'] as $detail) {
      $quantity = (float) ($detail['cantidad'] ?? 0);
      $unitPrice = (float) ($detail['precio_unitario_tipo_dolares'] ?? $detail['precio_unitario'] ?? 0);
      $subtotal = $quantity * $unitPrice;
      $totalUsd += $subtotal;

      $productName = $detail['producto']['nombre']
        ?? $detail['producto_nombre']
        ?? ('Producto #' . ($detail['id_producto'] ?? 'N/A'));

      $rows .= sprintf(
        '<tr><td class="center">%s</td><td>%s</td><td class="right">%s</td><td class="right">%s</td></tr>',
        $this->formatNumber($quantity),
        $this->escape($productName),
        $this->money($unitPrice),
        $this->money($subtotal),
      );
    }

    $provider = $purchase['proveedor'] ?? [];
    $content = '
      <h1>FACTURA DE COMPRA</h1>
      <table class="meta">
        <tr><th>Compra</th><td>#' . str_pad((string) $purchase['id'], 6, '0', STR_PAD_LEFT) . '</td></tr>
        <tr><th>Fecha</th><td>' . $this->escape($this->dateTime($purchase['fecha_creacion'] ?? null)) . '</td></tr>
        <tr><th>Proveedor</th><td>' . $this->escape((string) ($provider['nombre'] ?? 'N/A')) . '</td></tr>
        <tr><th>RIF</th><td>' . $this->escape((string) ($provider['rif'] ?? 'N/A')) . '</td></tr>
        <tr><th>Teléfono</th><td>' . $this->escape((string) ($provider['telefono'] ?? 'N/A')) . '</td></tr>
        <tr><th>Tasa</th><td>' . $this->escape(number_format((float) ($purchase['cotizacion_dolar_bolivares'] ?? 1), 2) . ' Bs/$') . '</td></tr>
      </table>
      <h2>Detalle de productos</h2>
      <table class="lines">
        <thead>
          <tr><th>Cant.</th><th>Producto</th><th>Precio Unit. ($)</th><th>Subtotal ($)</th></tr>
        </thead>
        <tbody>' . $rows . '</tbody>
      </table>
      <table class="totals">
        <tr><th>Total (USD)</th><td>' . $this->money($totalUsd) . '</td></tr>
      </table>
    ';

    $this->streamDocument("compra_{$purchase['id']}.pdf", $this->layout($business, $content, '#dc2626'));
  }

  public function streamLayaway(array $layaway, array $business): void
  {
    $rows = '';
    foreach ($layaway['detalles'] as $detail) {
      $quantity = (float) ($detail['cantidad'] ?? 0);
      $unitPrice = (float) ($detail['precio_unitario'] ?? 0);
      $subtotal = $quantity * $unitPrice;
      $productName = $detail['producto']['nombre']
        ?? $detail['producto_nombre']
        ?? ('Producto #' . ($detail['id_producto'] ?? 'N/A'));

      $rows .= sprintf(
        '<tr><td class="center">%s</td><td>%s</td><td class="right">%s</td><td class="right">%s</td></tr>',
        $this->formatNumber($quantity),
        $this->escape($productName),
        $this->money($unitPrice),
        $this->money($subtotal),
      );
    }

    $paymentRows = '';
    foreach ($layaway['pagos'] as $payment) {
      $paymentRows .= sprintf(
        '<tr><td>%s</td><td class="right">%s</td><td>%s</td></tr>',
        $this->escape($this->dateTime($payment['fecha_pago'] ?? $payment['fecha'] ?? null)),
        $this->money((float) ($payment['monto'] ?? 0)),
        $this->escape((string) ($payment['observacion'] ?? '-')),
      );
    }

    $client = $layaway['cliente'] ?? [];
    $pending = (float) ($layaway['monto_total'] ?? 0) - (float) ($layaway['monto_pagado'] ?? 0);
    $content = '
      <h1>COMPROBANTE DE APARTADO</h1>
      <table class="meta">
        <tr><th>Apartado</th><td>#' . str_pad((string) $layaway['id'], 6, '0', STR_PAD_LEFT) . '</td></tr>
        <tr><th>Fecha</th><td>' . $this->escape($this->dateTime($layaway['fecha_creacion'] ?? null)) . '</td></tr>
        <tr><th>Fecha límite</th><td>' . $this->escape($this->dateTime($layaway['fecha_limite'] ?? null)) . '</td></tr>
        <tr><th>Cliente</th><td>' . $this->escape(trim(($client['nombre'] ?? '') . ' ' . ($client['apellidos'] ?? ''))) . '</td></tr>
        <tr><th>Cédula</th><td>' . $this->escape((string) ($client['cedula'] ?? 'N/A')) . '</td></tr>
        <tr><th>Teléfono</th><td>' . $this->escape((string) ($client['telefono'] ?? 'N/A')) . '</td></tr>
        <tr><th>Estado</th><td>' . $this->escape((string) ($layaway['estado'] ?? 'N/A')) . '</td></tr>
      </table>
      <h2>Productos apartados</h2>
      <table class="lines">
        <thead>
          <tr><th>Cant.</th><th>Producto</th><th>Precio Unit. ($)</th><th>Subtotal ($)</th></tr>
        </thead>
        <tbody>' . $rows . '</tbody>
      </table>
      <table class="totals">
        <tr><th>Total</th><td>' . $this->money((float) ($layaway['monto_total'] ?? 0)) . '</td></tr>
        <tr><th>Pagado</th><td>' . $this->money((float) ($layaway['monto_pagado'] ?? 0)) . '</td></tr>
        <tr><th>Pendiente</th><td>' . $this->money($pending) . '</td></tr>
      </table>';

    if ($paymentRows !== '') {
      $content .= '
      <h2>Pagos registrados</h2>
      <table class="lines">
        <thead>
          <tr><th>Fecha</th><th>Monto ($)</th><th>Observación</th></tr>
        </thead>
        <tbody>' . $paymentRows . '</tbody>
      </table>';
    }

    $this->streamDocument("apartado_{$layaway['id']}.pdf", $this->layout($business, $content, '#d97706'));
  }

  public function streamRefund(array $refund, array $business): void
  {
    $content = '
      <h1>COMPROBANTE DE REEMBOLSO</h1>
      <table class="meta">
        <tr><th>Reembolso</th><td>#' . str_pad((string) $refund['id'], 6, '0', STR_PAD_LEFT) . '</td></tr>
        <tr><th>Fecha</th><td>' . $this->escape($this->dateTime($refund['fecha'] ?? null)) . '</td></tr>
        <tr><th>Venta</th><td>#' . $this->escape((string) ($refund['id_venta'] ?? 'N/A')) . '</td></tr>
        <tr><th>Procesado por</th><td>' . $this->escape((string) ($refund['usuario']['nombre'] ?? 'N/A')) . '</td></tr>
        <tr><th>Monto (USD)</th><td>' . $this->money((float) ($refund['monto_dolares'] ?? 0)) . '</td></tr>
        <tr><th>Monto (Bs)</th><td>' . $this->escape(number_format((float) ($refund['monto_bolivares'] ?? 0), 2) . ' Bs') . '</td></tr>
        <tr><th>Tasa</th><td>' . $this->escape(number_format((float) ($refund['tasa_cambio'] ?? 0), 2) . ' Bs/$') . '</td></tr>
        <tr><th>Motivo</th><td>' . $this->escape((string) ($refund['motivo'] ?? '-')) . '</td></tr>
      </table>
      <p class="footer">Este documento es un comprobante de reembolso.</p>
    ';

    $this->streamDocument("reembolso_{$refund['id']}.pdf", $this->layout($business, $content, '#dc2626'));
  }

  public function streamSalesReport(array $rows, array $filters, string $filename, string $title): void
  {
    $tableRows = '';
    $total = 0.0;

    foreach ($rows as $row) {
      $total += (float) ($row['total'] ?? 0);
      $tableRows .= sprintf(
        '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td class="right">%s</td></tr>',
        $this->escape((string) $row['codigo']),
        $this->escape((string) $row['fecha']),
        $this->escape((string) $row['vendedor']),
        $this->escape((string) $row['cliente']),
        $this->money((float) ($row['total'] ?? 0)),
      );
    }

    if ($tableRows === '') {
      $tableRows = '<tr><td colspan="5" class="center">No hay datos para mostrar</td></tr>';
    }

    $filterItems = '';
    foreach ($filters as $filter) {
      $filterItems .= '<li>' . $this->escape($filter) . '</li>';
    }

    $content = '
      <h1>' . $this->escape($title) . '</h1>
      <div class="filters">
        <strong>Filtros aplicados</strong>' .
        ($filterItems === '' ? '<p>Sin filtros</p>' : '<ul>' . $filterItems . '</ul>') . '
      </div>
      <table class="lines">
        <thead>
          <tr><th>ID</th><th>Fecha</th><th>Vendedor</th><th>Cliente</th><th>Total ($)</th></tr>
        </thead>
        <tbody>' . $tableRows . '</tbody>
      </table>
      <table class="totals">
        <tr><th>Total general</th><td>' . $this->money($total) . '</td></tr>
      </table>
    ';

    $this->streamDocument($filename, $this->layout([], $content, '#7c3aed'));
  }

  private function layout(array $business, string $content, string $accent): string
  {
    $businessName = $this->escape((string) ($business['nombre'] ?? 'Sistema de Gestión Administrativo'));
    $businessRif = $this->escape((string) ($business['rif'] ?? ''));
    $businessPhone = $this->escape((string) ($business['telefono'] ?? ''));
    $businessAddress = $this->escape((string) ($business['direccion'] ?? ''));
    $headerDetails = trim(implode(' | ', array_filter([$businessRif !== '' ? "RIF: {$businessRif}" : '', $businessPhone !== '' ? "Tel: {$businessPhone}" : ''])));

    return '<!doctype html>
      <html lang="es">
      <head>
        <meta charset="utf-8">
        <style>
          body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
          h1 { color: ' . $accent . '; text-align: center; font-size: 22px; margin: 0 0 12px; }
          h2 { color: ' . $accent . '; font-size: 15px; margin: 22px 0 10px; }
          .business { text-align: center; margin-bottom: 18px; }
          .business .name { font-size: 24px; font-weight: bold; color: ' . $accent . '; }
          .business .line { color: #6b7280; margin-top: 4px; }
          table { width: 100%; border-collapse: collapse; }
          .meta th, .meta td { border: 1px solid #d1d5db; padding: 8px; text-align: left; vertical-align: top; }
          .meta th { width: 28%; background: #f3f4f6; color: ' . $accent . '; }
          .lines th, .lines td { border: 1px solid #d1d5db; padding: 8px; }
          .lines thead th { background: ' . $accent . '; color: #fff; }
          .right { text-align: right; }
          .center { text-align: center; }
          .totals { margin-top: 14px; }
          .totals th, .totals td { padding: 8px; border-top: 1px solid #d1d5db; text-align: right; }
          .totals th { width: 70%; color: ' . $accent . '; }
          .filters { margin: 12px 0 16px; padding: 10px 12px; background: #f9fafb; border: 1px solid #e5e7eb; }
          .filters ul { margin: 8px 0 0 18px; padding: 0; }
          .footer { margin-top: 22px; text-align: center; color: #6b7280; font-size: 10px; }
          small { color: #6b7280; }
        </style>
      </head>
      <body>
        <div class="business">
          <div class="name">' . $businessName . '</div>
          ' . ($headerDetails !== '' ? '<div class="line">' . $headerDetails . '</div>' : '') . '
          ' . ($businessAddress !== '' ? '<div class="line">' . $businessAddress . '</div>' : '') . '
        </div>
        ' . $content . '
      </body>
      </html>';
  }

  private function streamDocument(string $filename, string $html): void
  {
    $options = new Options;
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('letter');
    $dompdf->render();

    while (ob_get_level() > 0) {
      ob_end_clean();
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    echo $dompdf->output();
  }

  private function money(float $amount): string
  {
    return '$ ' . number_format($amount, 2);
  }

  private function dateTime(null|string $value): string
  {
    if (!$value) {
      return 'N/A';
    }

    $timestamp = strtotime($value);

    if ($timestamp === false) {
      return $value;
    }

    return date('d/m/Y H:i', $timestamp);
  }

  private function formatNumber(float $value): string
  {
    if ((int) $value === $value) {
      return (string) (int) $value;
    }

    return number_format($value, 2);
  }

  private function escape(string $value): string
  {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }
}
