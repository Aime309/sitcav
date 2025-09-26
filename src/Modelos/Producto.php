<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property ?string $codigo
 * @property string $nombre
 * @property string $descripcion
 * @property string $url_imagen
 * @property float $precio_unitario_actual_dolares
 * @property float $precio_unitario_actual_bcv
 * @property int $cantidad_disponible
 * @property ?int $dias_garantia
 * @property int $dias_apartado
 * @property-read Categoria $categoria
 * @property-read Proveedor $proveedor
 * @property-read Marca $marca
 * @property-read Collection<DetalleCompra> $detalles_compras
 * @property-read Collection<DetalleVenta> $detalles_ventas
 */
final class Producto extends Model
{
  protected $table = 'productos';
  public $timestamps = false;

  protected $casts = [
    'precio_unitario_actual_dolares' => 'float',
    'precio_unitario_actual_bcv' => 'float',
  ];

  protected $hidden = [
    'id_categoria',
    'id_proveedor',
    'id_marca',
  ];

  /**
   * @return BelongsTo<Categoria>
   * @deprecated Usa `categoria` en su lugar.
   */
  function categoria(): BelongsTo
  {
    return $this->belongsTo(Categoria::class, 'id_categoria');
  }

  /**
   * @return BelongsTo<Proveedor>
   * @deprecated Usa `proveedor` en su lugar.
   */
  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  /**
   * @return BelongsTo<Marca>
   * @deprecated Usa `marca` en su lugar.
   */
  function marca(): BelongsTo
  {
    return $this->belongsTo(Marca::class, 'id_marca');
  }

  /**
   * @return HasMany<DetalleCompra>
   * @deprecated Usa `detalles_compras` en su lugar.
   */
  function detalles_compras(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_producto');
  }

  /**
   * @return HasMany<DetalleVenta>
   * @deprecated Usa `detalles_ventas` en su lugar.
   */
  function detalles_ventas(): HasMany
  {
    return $this->hasMany(DetalleVenta::class, 'id_producto');
  }
}
