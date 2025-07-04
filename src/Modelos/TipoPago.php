<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property-read Usuario $encargado
 * @property-read Collection<Pago> $pagos
 */
final class TipoPago extends Model
{
  protected $table = 'tipos_pago';
  public $timestamps = false;

  /**
   * @return BelongsTo<Usuario>
   * @deprecated Usa `encargado` en su lugar.
   */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_encargado');
  }

  /**
   * @return HasMany<Pago>
   * @deprecated Usa `pagos` en su lugar.
   */
  function pagos(): HasMany
  {
    return $this->hasMany(Pago::class, 'id_tipo_pago');
  }
}
