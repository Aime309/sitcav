<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
