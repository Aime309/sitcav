<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $nombre
 * @property string $apellido
 * @property string $correo
 * @property string $telefono
 * @property string $clave
 * @property array<int, 'Administrador'|'Encargado'|'Vendedor'> $roles
 * @property string[] $imagenes
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 * @property Collection<int, Negocio> $negocios
 */
#[Table(keyType: 'string', incrementing: false)]
final class Usuario extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [
        'activo' => 1,
        'imagenes' => '[]',
    ];

    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class);
    }
}
