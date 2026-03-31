<?php

declare(strict_types=1);

namespace App\Models;

final class Refund extends Model
{
  protected static function getTableName(): string
  {
    return 'reembolsos';
  }
}
