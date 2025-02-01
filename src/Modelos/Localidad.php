<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $nombre
 */
final class Localidad extends Model
{
  protected $table = 'localidades';

  function sectores(): HasMany
  {
    return $this->hasMany(Sector::class, 'id_localidad');
  }

  function estado(): BelongsTo
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }
}
