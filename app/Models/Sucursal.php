<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Override;
use Illuminate\Support\Str;

/**
 * @property-read string $slug
 * @property-read string $nombre
 * @property-read string $direccion
 * @property-read string $telefono
 * @property-read Collection<int, Usuario> $empleados
 * @property-read Negocio $negocio
 */
#[Fillable('nombre', 'direccion', 'telefono', 'negocio_slug')]
#[Table(name: 'sucursales', key: 'slug', keyType: 'string')]
#[WithoutIncrementing]
#[WithoutTimestamps]
final class Sucursal extends Model
{
    public $usesUniqueIds = true;

    #[Override]
    public function newUniqueId()
    {
        return Str::slug($this->nombre);
    }

    #[Override]
    public function uniqueIds()
    {
        return ['slug'];
    }

    /** @return BelongsToMany<Usuario, $this> */
    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuarios_establecimientos',
        );
    }

    /** @return BelongsTo<Negocio, $this> */
    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}
