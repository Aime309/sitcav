<?php

declare(strict_types=1);

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Marca extends Model
{
  protected $table = 'marcas';
  public $timestamps = false;

  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_marca');
  }

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }
}
