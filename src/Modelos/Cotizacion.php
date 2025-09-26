<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $fecha_hora_creacion
 * @property-read float $tasa_bcv
 * @property-read Usuario $encargado
 */
final class Cotizacion extends Model
{
  const CREATED_AT = 'fecha_hora_creacion';
  const UPDATED_AT = null;

  protected $table = 'cotizaciones';

  protected $casts = [
    'fecha_hora_creacion' => 'datetime',
    'tasa_bcv' => 'float',
  ];

  /**
   * @return BelongsTo<Usuario>
   * @deprecated Usa `encargado` en su lugar.
   */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_encargado');
  }
}
