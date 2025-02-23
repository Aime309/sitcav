<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read float $monto
 */
final class Pago extends Model
{
  protected $table = 'pagos';

  function venta(): BelongsTo
  {
    return $this->belongsTo(DetalleVenta::class, 'id_detalle_venta');
  }

  function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoPago::class, 'id_tipo_pago');
  }
}
