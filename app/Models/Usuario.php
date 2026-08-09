<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $correo
 * @property-read string $clave
 * @property-read Collection<UsuarioRol> $roles
 * @property-read Collection<int, Negocio> $negocios
 * @property-read Collection<int, Sucursal> $sucursales
 */
#[Fillable('correo', 'clave')]
#[Table(key: 'correo', keyType: 'string')]
#[WithoutIncrementing]
#[WithoutTimestamps]
final class Usuario extends Model
{
    /** @return HasMany<UsuarioRol, $this> */
    public function roles(): HasMany
    {
        return $this->hasMany(UsuarioRol::class);
    }

    /** @return BelongsToMany<Negocio, $this> */
    public function negocios(): BelongsToMany
    {
        return $this->belongsToMany(
            Negocio::class,
            'usuarios_establecimientos',
        );
    }

    /** @return BelongsToMany<Sucursal, $this> */
    public function sucursales(): BelongsToMany
    {
        return $this->belongsToMany(
            Sucursal::class,
            'usuarios_establecimientos',
        );
    }
}
