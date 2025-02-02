<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Producto extends Model
{
  protected $table = 'productos';

  function categoria(): BelongsTo
  {
    return $this->belongsTo(CategoriaProducto::class, 'id_categoria');
  }

  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  function compras(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_producto');
  }

  function ventas(): HasMany
  {
    return $this->hasMany(DetalleVenta::class, 'id_producto');
  }
}
