<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $fecha_hora_creacion
 * @property float $tasa_bcv
 * @property float $monto_dolares
 * @property-read TipoPago $tipo
 * @property-read DetalleVenta $detalle_venta
 */
final class Pago extends Model
{
  protected $table = 'pagos';
  public $timestamps = false;

  /**
   * @return BelongsTo<TipoPago>
   * @deprecated Usa `tipo` en su lugar.
   */
  function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoPago::class, 'id_tipo_pago');
  }

  /**
   * @return BelongsTo<DetalleVenta>
   * @deprecated Usa `detalle_venta` en su lugar.
   */
  function detalle_venta(): BelongsTo
  {
    return $this->belongsTo(DetalleVenta::class, 'id_detalle_venta');
  }
}
