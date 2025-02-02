<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TipoPago extends Model
{
  protected $table = 'tipos_pago';

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }

  function pagos(): HasMany
  {
    return $this->hasMany(Pago::class, 'id_tipo_pago');
  }
}
