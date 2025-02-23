<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read float $subtotal
 * @property-read ?Collection<int, Pago> $pagos
 */
final class DetalleVenta extends Model
{
  protected $table = 'detalles_ventas';

  function producto(): BelongsTo
  {
    return $this->belongsTo(Producto::class, 'id_producto');
  }

  function venta(): BelongsTo
  {
    return $this->belongsTo(Venta::class, 'id_venta');
  }

  function pagos(): HasMany
  {
    return $this->hasMany(Pago::class, 'id_detalle_venta');
  }

  function getSubtotalAttribute(): float
  {
    return $this->cantidad * $this->precio_unitario_fijo_dolares;
  }
}
