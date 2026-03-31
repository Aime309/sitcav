<?php

declare(strict_types=1);

namespace App\Models;

final class Provider extends Model
{
  protected static function getTableName(): string
  {
    return 'proveedores';
  }
}
