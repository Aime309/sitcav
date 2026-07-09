<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $usuario_id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property string $slug
 * @property string[] $imagenes
 * @property int $carga_inicial_cerrada
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 * @property Collection<int, Producto> $productos
 * @property Collection<int, Sucursal> $sucursales
 */
#[Table(keyType: 'string', incrementing: false)]
final class Negocio extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [
        'carga_inicial_cerrada' => 0,
        'activo' => 1,
        'imagenes' => '[]',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }
}
