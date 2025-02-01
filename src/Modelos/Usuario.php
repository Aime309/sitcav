<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read int $cedula
 * @property-read 'Administrador'|'Vendedor' $rol
 * @property-read bool $esta_activo
 * @property-read string $pregunta_secreta
 */
final class Usuario extends Model
{
  protected $table = 'usuarios';
  protected $hidden = ['clave', 'respuesta_secreta'];

  function administrador(): BelongsTo
  {
    return $this->belongsTo(self::class, 'id_admin');
  }

  function vendedores(): HasMany
  {
    return $this->hasMany(self::class, 'id_admin');
  }
}
