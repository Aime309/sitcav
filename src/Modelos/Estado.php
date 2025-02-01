<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $nombre
 */
final class Estado extends Model
{
  protected $table = 'estados';

  function localidades(): HasMany
  {
    return $this->hasMany(Localidad::class, 'id_estado');
  }
}
