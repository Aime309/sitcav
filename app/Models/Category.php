<?php

declare(strict_types=1);

namespace App\Models;

final class Category extends Model
{
  protected static function getTableName(): string
  {
    return 'categorias';
  }
}
