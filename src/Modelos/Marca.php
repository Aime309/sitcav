<?php

declare(strict_types=1);

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property string $url_imagen
 * @property-read Usuario $encargado
 * @property-read Collection<Producto> $productos
 */
final class Marca extends Model
{
  protected $table = 'marcas';
  public $timestamps = false;

  /**
   * @return BelongsTo<Usuario>
   * @deprecated Usa encargado en su lugar.
   */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_encargado');
  }

  /**
   * @return HasMany<Producto>
   * @deprecated Usa productos en su lugar.
   */
  function productos(): HasMany
  {
    return $this->hasMany(Producto::class, 'id_marca');
  }
}
