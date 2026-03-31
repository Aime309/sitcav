<?php

declare(strict_types=1);

namespace App\Models;

final class Layaway extends Model
{
  protected static function getTableName(): string
  {
    return 'apartados';
  }

  public function countActiveLayaways(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table WHERE estado = 'activo'")
      ->column();
    }
}
