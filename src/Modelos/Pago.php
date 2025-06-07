<?php

namespace SITCAV\Modelos;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read DateTimeInterface $fechaHora
 * @property-read float $cotizacionDolarBolivares
 * @property-read float $monto
 * @property-read TipoPago $tipo
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
