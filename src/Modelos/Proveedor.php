<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $rif
 * @property string $nombre
 * @property string $telefono
 * @property-read Estado $estado
 * @property-read ?Localidad $localidad
 * @property-read ?Sector $sector
 * @property-read Collection<Producto> $productos
 * @property-read Collection<Compra> $compras
 */
final class Proveedor extends Model
{
  protected $table = 'proveedores';
  public $timestamps = false;

  /**
   * @return BelongsTo<Estado>
   * @deprecated Usa `estado` en su lugar.
   */
  function estado(): BelongsTo
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  /**
   * @return BelongsTo<Localidad>
   * @deprecated Usa `localidad` en su lugar.
   */
  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  /**
   * @return BelongsTo<Sector>
   * @deprecated Usa `sector` en su lugar.
   */
  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  /**
   * @return HasMany<Producto>
   * @deprecated Usa `productos` en su lugar.
   */
  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_proveedor');
  }

  /**
   * @return HasMany<Compra>
   * @deprecated Usa `compras` en su lugar.
   */
  function compras(): HasMany
  {
    return $this->hasMany(Compra::class, 'id_proveedor');
  }
}
