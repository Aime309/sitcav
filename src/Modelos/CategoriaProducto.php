<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class CategoriaProducto extends Model
{
  protected $table = 'categorias_producto';

  function usuario(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_usuario');
  }

  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_categoria');
  }
}
