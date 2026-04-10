<?php

declare(strict_types=1);

namespace App\Models;

final class Sale extends Model
{
  protected static function getTableName(): string
  {
    return 'ventas';
  }

  public function countMonthSales(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table WHERE strftime('%Y-%m', fecha_creacion) = strftime('%Y-%m', 'now')")
      ->column();
  }

  public function sumDailySales(string $date): float
  {
    return (float) $this->db->query("
      SELECT SUM(dv.precio_unitario_tipo_dolares * dv.cantidad) AS total
      FROM ventas v
      JOIN detalles_ventas dv ON v.id = dv.id_venta
      WHERE date(v.fecha_creacion) = date(?)
    ", $date)->column();
  }

  public function getTopProducts(int $limit = 5): array
  {
    return $this->db->query("
      SELECT p.nombre, SUM(dv.cantidad) as total_vendido
      FROM productos p
      JOIN detalles_ventas dv ON p.id = dv.id_producto
      GROUP BY p.id
      ORDER BY total_vendido DESC
      LIMIT ?
    ", $limit)->all();
  }

  public function getSalesByCategory(): array
  {
    return $this->db->query("
      SELECT c.nombre, SUM(dv.cantidad) as total
      FROM categorias c
      JOIN productos p ON c.id = p.id_categoria
      JOIN detalles_ventas dv ON p.id = dv.id_producto
      GROUP BY c.id
    ")->all();
  }
}
