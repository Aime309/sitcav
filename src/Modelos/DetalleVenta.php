<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DetalleVenta extends Model
{
  protected $table = 'detalles_ventas';

  function producto(): BelongsTo
  {
    return $this->belongsTo(Producto::class, 'id_producto');
  }

  function venta(): BelongsTo
  {
    return $this->belongsTo(Venta::class, 'id_venta');
  }

  function pagos(): HasMany
  {
    return $this->hasMany(Pago::class, 'id_detalle_venta');
  }
}
