<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Compra extends Model
{
  protected $table = 'compras';

  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  function detalles(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_compra');
  }
}
