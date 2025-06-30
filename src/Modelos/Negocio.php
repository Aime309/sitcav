<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $nombre
 * @property string $rif
 * @property string $telefono
 * @property-read Localidad $localidad
 * @property-read ?Sector $sector
 */
final class Negocio extends Model
{
  protected $table = 'negocios';
  public $timestamps = false;

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
}
