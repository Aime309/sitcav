<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read int $cantidad
 * @property-read bool $estaApartado
 * @property-read Pago[] $pagos
 * @property-read float $precioUnitarioFijoDolares
 * @property-read int $cantidad
 * @property-read Producto $producto
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

  function getPrecioUnitarioFijoDolaresAttribute(): float
  {
    return $this->attributes['precio_unitario_fijo_dolares'];
  }

  function getEstaApartadoAttribute(): bool
  {
    return $this->attributes['esta_apartado'];
  }
}
