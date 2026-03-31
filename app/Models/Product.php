<?php

declare(strict_types=1);

namespace App\Models;

final class Product extends Model
{
  protected static function getTableName(): string
  {
    return 'productos';
  }

  public function countWithLowStock(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table WHERE cantidad_disponible <= 5")
      ->column();
  }
}
