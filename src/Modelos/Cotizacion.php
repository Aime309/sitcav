<?php

namespace SITCAV\Modelos;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read DateTimeInterface $fechaHora
 * @property-read float $tasaDolarBolivares
 */
final class Cotizacion extends Model
{
  protected $table = 'cotizaciones';

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }

  function getFechaHoraAttribute(): DateTimeInterface
  {
    return new DateTimeImmutable($this->attributes['fecha_hora']);
  }

  function getTasaDolarBolivaresAttribute(): float
  {
    return $this->attributes['tasa_dolar_bolivares'];
  }
}
