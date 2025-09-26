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
 * @property-read Collection<Localidad> $localidades
 * @property-read Collection<Proveedor> $proveedores
 */
final class Estado extends Model
{
  protected $table = 'estados';
  public $timestamps = false;

  protected $hidden = [
    'id_encargado',
  ];

  /**
   * @return BelongsTo<Usuario>
   * @deprecated Usa `encargado` en su lugar.
   */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_encargado');
  }

  /**
   * @return HasMany<Localidad>
   * @deprecated Usa `localidades` en su lugar.
   */
  function localidades(): HasMany
  {
    return $this->hasMany(Localidad::class, 'id_estado');
  }

  /**
   * @return HasMany<Proveedor>
   * @deprecated Usa `proveedores` en su lugar.
   */
  function proveedores(): HasMany
  {
    return $this->hasMany(Proveedor::class, 'id_estado');
  }
}
