<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Cliente extends Model
{
  protected $table = 'clientes';

  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  function ventas(): HasMany
  {
    return $this->hasMany(Venta::class, 'id_cliente');
  }
}
