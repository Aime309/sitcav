<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property-read Estado $estado
 * @property-read Collection<Sector> $sectores
 * @property-read Collection<Negocio> $negocios
 * @property-read Collection<Proveedor> $proveedores
 * @property-read Collection<Cliente> $clientes
 */
final class Localidad extends Model
{
  protected $table = 'localidades';
  public $timestamps = false;

  protected $hidden = [
    'id_estado',
  ];

  /**
   * @return BelongsTo<Estado>
   * @deprecated Usa `estado` en su lugar.
   */
  function estado(): BelongsTo
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  /**
   * @return HasMany<Estado>
   * @deprecated Usa `estado` en su lugar.
   */
  function sectores(): HasMany
  {
    return $this->hasMany(Sector::class, 'id_localidad');
  }

  /**
   * @return HasMany<Negocio>
   * @deprecated Usa `negocios` en su lugar.
   */
  function negocios(): HasMany
  {
    return $this->hasMany(Negocio::class, 'id_localidad');
  }

  /**
   * @return HasMany<Proveedor>
   * @deprecated Usa `proveedores` en su lugar.
   */
  function proveedores(): HasMany
  {
    return $this->hasMany(Proveedor::class, 'id_localidad');
  }

  /**
   * @return HasMany<Cliente>
   * @deprecated Usa `clientes` en su lugar.
   */
  function clientes(): HasMany
  {
    return $this->hasMany(Cliente::class, 'id_localidad');
  }
}
