<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $cantidad
 * @property-read float $precioUnitarioFijoDolares
 */
final class DetalleCompra extends Model
{
  protected $table = 'detalles_compras';

  function compra(): BelongsTo
  {
    return $this->belongsTo(Compra::class, 'id_compra');
  }

  function producto(): BelongsTo
  {
    return $this->belongsTo(Producto::class, 'id_producto');
  }

  function getPrecioUnitarioFijoDolaresAttribute(): float
  {
    return $this->attributes['precio_unitario_fijo_dolares'];
  }
}
