<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $correo
 * @property string $clave
 * @property-read Collection<UsuarioRol> $roles
 * @property Collection<int, Negocio> $negocios
 * @property ?Negocio $negocio
 * @property ?Sucursal $sucursal
 */
#[WithoutTimestamps]
#[Fillable('correo', 'clave')]
final class Usuario extends Model
{
    /** @return HasMany<UsuarioRol, $this> */
    public function roles(): HasMany
    {
        return $this->hasMany(UsuarioRol::class);
    }

    /** @return HasMany<Negocio, $this> */
    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class);
    }
}
