<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read ?Usuario $usuario
 */
final class Estado extends Model
{
  protected $table = 'estados';

  function localidades(): HasMany
  {
    return $this->hasMany(Localidad::class, 'id_estado');
  }

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }

  function proveedores(): HasMany
  {
    return $this->hasMany(Proveedor::class, 'id_estado');
  }
}
