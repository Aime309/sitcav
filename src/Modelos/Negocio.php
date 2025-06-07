<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $nombre
 * @property-read string $rif
 * @property-read string $telefono
 * @property-read Localidad $localidad
 * @property-read ?Sector $sector
 */
final class Negocio extends Model
{
  protected $table = 'negocios';

  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }
}
