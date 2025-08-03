<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $fecha_hora_creacion
 * @property-read Cliente $cliente
 * @property-read Collection<int, DetalleVenta> $detalles
 */
final class Venta extends Model
{
  protected $table = 'ventas';
  public $timestamps = false;

  protected $casts = [
    'fecha_hora_creacion' => 'datetime',
  ];

  /**
   * @return BelongsTo<Cliente>
   * @deprecated Use cliente instead.
   */
  function cliente(): BelongsTo
  {
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }

  /**
   * @return HasMany<DetalleVenta>
   * @deprecated Use detalles instead.
   */
  function detalles(): HasMany
  {
    return $this->hasMany(DetalleVenta::class, 'id_venta');
  }
}
