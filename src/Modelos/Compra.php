<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $fecha_hora_creacion
 * @property float $tasa_bcv
 * @property-read Proveedor $proveedor
 * @property-read Collection<DetalleCompra> $detalles
 */
final class Compra extends Model
{
  const CREATED_AT = 'fecha_hora_creacion';
  const UPDATED_AT = null;

  protected $table = 'compras';

  protected $casts = [
    'fecha_hora_creacion' => 'datetime',
    'tasa_bcv' => 'float',
  ];

  protected $fillable = ['tasa_bcv'];

  /**
   * @return BelongsTo<Proveedor>
   * @deprecated Usa `proveedor` en su lugar.
   */
  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  /**
   * @return HasMany<DetalleCompra>
   * @deprecated Usa `detalles` en su lugar.
   */
  function detalles(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_compra');
  }
}
