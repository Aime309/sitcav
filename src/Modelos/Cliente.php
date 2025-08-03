<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $cedula
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono
 * @property-read Localidad $localidad
 * @property-read ?Sector $sector
 * @property-read Collection<int, Venta> $compras
 */
final class Cliente extends Model
{
  protected $table = 'clientes';
  public $timestamps = false;

  private const PATRONES = [
    'nombres' => '/^[a-zA-ZáéíóúñÁÉÍÓÚÑ]{1,}\s?[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/',
  ];

  /**
   * @return BelongsTo<Localidad>
   * @deprecated Usa `localidad` en su lugar.
   */
  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  /**
   * @return BelongsTo<Sector>
   * @deprecated Usa `sector` en su lugar.
   */
  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  /**
   * @return HasMany<Venta>
   * @deprecated Usa `compras` en su lugar.
   */
  function compras(): HasMany
  {
    return $this->hasMany(Venta::class, 'id_cliente');
  }

  function __get($key)
  {
    switch ($key) {
      case 'deuda_acumulada_dolares':
        $deudaAcumuladaDolares = 0;

        foreach ($this->compras as $compra) {
          foreach ($compra->detalles as $detalle) {
            $subTotalDolares = $detalle->precio_unitario_fijo_dolares * $detalle->cantidad;
            $pagosAcumulados = 0;

            foreach ($detalle->pagos as $pago) {
              $pagosAcumulados += $pago->monto_dolares;
            }

            $deudaAcumuladaDolares += $subTotalDolares - $pagosAcumulados;
          }
        }

        return $deudaAcumuladaDolares;
    }
  }
}
