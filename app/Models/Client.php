<?php

declare(strict_types=1);

namespace App\Models;

final class Client extends Model
{
  protected static function getTableName(): string
  {
    return 'clientes';
  }
}
