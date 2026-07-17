<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $negocio_id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 * @property Collection<int, SucursalImagen> $imagenes
 * @property Collection<int, Usuario> $empleados
 * @property Negocio $negocio
 */
#[Fillable('id', 'nombre', 'rif', 'direccion', 'telefono')]
#[Table(name: 'sucursales', keyType: 'string', incrementing: false)]
final class Sucursal extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [
        'activo' => 1,
    ];

    /** @return HasMany<SucursalImagen> */
    public function imagenes(): HasMany
    {
        return $this->hasMany(SucursalImagen::class);
    }

    /** @return BelongsToMany<Usuario, $this> */
    public function empleados(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_establecimientos');
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class);
    }
}
