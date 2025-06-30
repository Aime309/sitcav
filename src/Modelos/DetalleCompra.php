<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property float $precio_unitario_fijo_dolares
 * @property float $precio_unitario_fijo_bcv
 * @property int $cantidad
 * @property-read Compra $compra
 * @property-read Producto $producto
 */
final class DetalleCompra extends Model
{
  protected $table = 'detalles_compra';
  public $timestamps = false;

  protected $casts = [
    'precio_unitario_fijo_dolares' => 'float',
    'precio_unitario_fijo_bcv' => 'float',
  ];

  /**
   * @return BelongsTo<Compra>
   * @deprecated Usa `compra` en su lugar.
   */
  function compra(): BelongsTo
  {
    return $this->belongsTo(Compra::class, 'id_compra');
  }

  /**
   * @return BelongsTo<Compra>
   * @deprecated Usa `producto` en su lugar.
   */
  function producto(): BelongsTo
  {
    return $this->belongsTo(Producto::class, 'id_producto');
  }
}
