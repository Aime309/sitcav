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
 * @property-read Collection<Compra> $ventas
 */
final class Proveedor extends Model
{
  protected $table = 'proveedores';
  public $timestamps = false;

  /**
   * @return BelongsTo<Estado>
   */
  function estado(): BelongsTo
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  /**
   * @return BelongsTo<Localidad>
   */
  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  /**
   * @return BelongsTo<Sector>
   */
  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  /**
   * @return HasMany<Producto>
   */
  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_proveedor');
  }

  /**
   * @return HasMany<Compra>
   */
  function ventas(): HasMany
  {
    return $this->hasMany(Compra::class, 'id_proveedor');
  }
}
