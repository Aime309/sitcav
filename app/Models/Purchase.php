<?php

declare(strict_types=1);

namespace App\Models;

final class Purchase extends Model
{
  protected static function getTableName(): string
  {
    return 'compras';
  }
}
