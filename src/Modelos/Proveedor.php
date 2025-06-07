<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Estado $estado
 * @property-read ?Localidad $localidad
 * @property-read ?Sector $sector
 */
final class Proveedor extends Model
{
  protected $table = 'proveedores';

  function estado(): BelongsTo
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_proveedor');
  }

  function compras(): HasMany
  {
    return $this->hasMany(Compra::class, 'id_proveedor');
  }
}
