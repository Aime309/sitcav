<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Sector extends Model
{
  protected $table = 'sectores';

  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  function clientes(): HasMany
  {
    return $this->hasMany(Cliente::class, 'id_sector');
  }

  function negocios(): HasMany
  {
    return $this->hasMany(Negocio::class, 'id_sector');
  }

  function proveedores(): HasMany
  {
    return $this->hasMany(Proveedor::class, 'id_sector');
  }
}
