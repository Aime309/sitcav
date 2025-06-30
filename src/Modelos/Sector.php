<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property-read Localidad $localidad
 * @property-read Collection<Negocio> $negocios
 * @property-read Collection<Proveedor> $proveedores
 * @property-read Collection<Cliente> $clientes
 */
final class Sector extends Model
{
  protected $table = 'sectores';
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
   * @return HasMany<Negocio>
   * @deprecated Usa `negocios` en su lugar.
   */
  function negocios(): HasMany
  {
    return $this->hasMany(Negocio::class, 'id_sector');
  }

  /**
   * @return HasMany<Proveedor>
   * @deprecated Usa `proveedores` en su lugar.
   */
  function proveedores(): HasMany
  {
    return $this->hasMany(Proveedor::class, 'id_sector');
  }

  /**
   * @return HasMany<Cliente>
   * @deprecated Usa `clientes` en su lugar.
   */
  function clientes(): HasMany
  {
    return $this->hasMany(Cliente::class, 'id_sector');
  }
}
