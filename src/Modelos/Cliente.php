<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $cedula
 * @property-read string $nombres
 * @property-read string $apellidos
 * @property-read string $telefono
 */
final class Cliente extends Model
{
  protected $table = 'clientes';

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

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }
}
