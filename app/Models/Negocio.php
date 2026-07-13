<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $usuario_id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property string $slug
 * @property int $carga_inicial_abierta
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 * @property Collection<int, Producto> $productos
 * @property Collection<int, Sucursal> $sucursales
 * @property Collection<int, Proveedor> $proveedores
 * @property Collection<int, Cliente> $clientes
 * @property Collection<int, Reserva> $reservas
 * @property Collection<int, NegocioImagen> $imagenes
 * @property Collection<int, Usuario> $empleados
 */
#[Table(keyType: 'string', incrementing: false)]
#[Fillable('id', 'nombre', 'rif', 'direccion', 'telefono', 'slug')]
final class Negocio extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [
        'carga_inicial_abierta' => 1,
        'activo' => 1,
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

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

    /** @return HasMany<NegocioImagen> */
    public function imagenes(): HasMany
    {
        return $this->hasMany(NegocioImagen::class);
    }

    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'asignaciones');
    }
}
