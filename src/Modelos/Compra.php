<?php

namespace SITCAV\Modelos;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read DateTimeInterface $fechaHora
 * @property-read float $cotizacionDolarBolivares
 * @property-read Proveedor $proveedor
 * @property-read DetalleCompra[] $detalles
 */
final class Compra extends Model
{
  protected $table = 'compras';

  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  /**
   * @return HasMany<DetalleCompra>
   */
  function detalles(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_compra');
  }

  function getFechaHoraAttribute(): DateTimeInterface
  {
    return new DateTimeImmutable($this->attributes['fecha_hora']);
  }

  function getCotizacionDolarBolivaresAttribute(): float
  {
    return $this->attributes['cotizacion_dolar_bolivares'];
  }
}
