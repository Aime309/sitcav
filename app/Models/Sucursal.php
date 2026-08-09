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
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property Collection<int, Usuario> $empleados
 * @property-read Negocio $negocio
 */
#[Table(name: 'sucursales', key: 'slug', keyType: 'string')]
#[WithoutIncrementing]
#[WithoutTimestamps]
#[Fillable('nombre', 'direccion', 'telefono', 'negocio_slug')]
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
