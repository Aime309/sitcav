<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read ?self $administrador
 */
class Usuario extends Model
{
  protected $table = 'usuarios';

  function administrador(): BelongsTo
  {
    return $this->belongsTo(self::class, 'id_admin');
  }

  function vendedores(): HasMany
  {
    return $this->hasMany(self::class, 'id_admin');
  }

  function cotizaciones(): HasMany
  {
    return $this->hasMany(Cotizacion::class, 'id_usuario');
  }

  function estados(): HasMany
  {
    return $this->hasMany(Estado::class, 'id_usuario');
  }

  function categoriasProducto(): HasMany
  {
    return $this->hasMany(CategoriaProducto::class, 'id_usuario');
  }

  function tiposPago(): HasMany
  {
    return $this->hasMany(TipoPago::class, 'id_usuario');
  }

  function marcas(): HasMany
  {
    return $this->hasMany(Marca::class, 'id_usuario');
  }
}
