<?php

declare(strict_types=1);

namespace App\Models;

final class InventoryMovement extends Model
{
  protected static function getTableName(): string
  {
    return 'movimientos_inventario';
  }
}
