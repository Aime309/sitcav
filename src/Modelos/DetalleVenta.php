<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $cantidad
 * @property float $precio_unitario_fijo_dolares
 * @property float $precio_unitario_fijo_bcv
 * @property bool $esta_apartado
 * @property-read Collection<int, Pago> $pagos
 * @property-read Producto $producto
 * @property-read Venta $venta
 */
final class DetalleVenta extends Model
{
  protected $table = 'detalles_venta';
  public $timestamps = false;

  protected $casts = [
    'precio_unitario_fijo_dolares' => 'float',
    'precio_unitario_fijo_bcv' => 'float',
    'esta_apartado' => 'boolean',
  ];

  /**
   * @return BelongsTo<Producto>
   * @deprecated Usa `producto` en su lugar.
   */
  function producto(): BelongsTo
  {
    return $this->belongsTo(Producto::class, 'id_producto');
  }

  /**
   * @return BelongsTo<Venta>
   * @deprecated Usa `venta` en su lugar.
   */
  function venta(): BelongsTo
  {
    return $this->belongsTo(Venta::class, 'id_venta');
  }

  /**
   * @return HasMany<Pago>
   * @deprecated Usa `pagos` en su lugar.
   */
  function pagos(): HasMany
  {
    return $this->hasMany(Pago::class, 'id_detalle_venta');
  }
}
