<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property-read Usuario $usuario
 */
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
