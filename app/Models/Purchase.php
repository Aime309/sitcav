<?php

declare(strict_types=1);

namespace App\Models;

final class Purchase extends Model
{
  protected static function getTableName(): string
  {
    return 'compras';
  }

  public function sumDailyPurchases(string $date): float
  {
    return (float) $this->db->query("
      SELECT SUM(dc.precio_unitario_tipo_dolares * dc.cantidad) AS total
      FROM compras c
      JOIN detalles_compras dc ON c.id = dc.id_compra
      WHERE date(c.fecha_creacion) = date(?)
    ", $date)->column();
  }
}
