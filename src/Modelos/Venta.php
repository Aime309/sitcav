<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read ?Collection<int, DetalleVenta> $detalles
 */
final class Venta extends Model
{
  protected $table = 'ventas';

  function cliente(): BelongsTo
  {
    return $this->belongsTo(Cliente::class, 'id_cliente');
  }

  function detalles(): HasMany
  {
    return $this->hasMany(DetalleVenta::class, 'id_venta');
  }
}
