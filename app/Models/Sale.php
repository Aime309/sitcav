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
}
