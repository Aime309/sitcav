<?php

declare(strict_types=1);

namespace App\Models;

final class ExchangeRate extends Model
{
  protected static function getTableName(): string
  {
    return 'cotizaciones';
  }

  public function current(): float
  {
    $column = $this
      ->db
      ->query("SELECT tasa_dolar_bolivares FROM $this->table ORDER BY fecha_hora DESC LIMIT 1")
      ->column();

    return floatval($column);
  }
}
