<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property Collection<int, Usuario> $empleados
 * @property-read Negocio $negocio
 */
#[Table(name: 'sucursales')]
#[WithoutTimestamps]
#[Fillable('nombre', 'direccion', 'telefono', 'negocio_slug')]
final class Sucursal extends Model
{
    /** @return BelongsToMany<Usuario, $this> */
    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_establecimientos');
    }

    /** @return BelongsTo<Negocio, $this> */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}
