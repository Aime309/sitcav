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
use Override;
use Illuminate\Support\Str;

/**
 * @property-read string $slug
 * @property-read string $nombre
 * @property-read string $rif
 * @property-read string $direccion
 * @property-read string $telefono
 * @property-read bool $carga_inicial_abierta
 * @property-read Collection<int, Usuario> $empleados
 * @property-read Collection<int, Sucursal> $sucursales
 */
#[Fillable(
    'slug',
    'nombre',
    'rif',
    'direccion',
    'telefono',
    'carga_inicial_abierta',
)]
#[Table(key: 'slug', keyType: 'string')]
#[WithoutIncrementing]
#[WithoutTimestamps]
final class Negocio extends Model
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

    /** @return HasMany<Cliente, $this> */
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
        return $this->belongsToMany(
            Usuario::class,
            'usuarios_establecimientos',
        );
    }
}
