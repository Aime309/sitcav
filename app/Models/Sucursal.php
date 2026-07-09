<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $negocio_id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property string[] $imagenes
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 */
#[Table(name: 'sucursales', keyType: 'string', incrementing: false)]
final class Sucursal extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [
        'activo' => 1,
        'imagenes' => '[]',
    ];
}
