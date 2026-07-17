<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property string $slug
 * @property 0|1 $carga_inicial_abierta
 * @property Collection<int, Producto> $productos
 * @property Collection<int, Sucursal> $sucursales
 * @property Collection<int, Proveedor> $proveedores
 * @property Collection<int, Cliente> $clientes
 * @property Collection<int, Reserva> $reservas
 * @property Collection<int, Usuario> $empleados
 */
#[WithoutTimestamps]
#[Fillable(
    'nombre',
    'rif',
    'direccion',
    'telefono',
    'slug',
    'carga_inicial_abierta',
)]
final class Negocio extends Model
{
    /** @return HasMany<Producto> */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    /** @return HasMany<Sucursal> */
    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }

    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /** @return BelongsToMany<Usuario, $this> */
    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_establecimientos');
    }
}
