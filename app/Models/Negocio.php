<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $usuario_id
 * @property string $nombre
 * @property string $rif
 * @property string $direccion
 * @property string $telefono
 * @property string $slug
 * @property string $imagenes
 * @property int $carga_inicial_cerrada
 * @property int $activo
 * @property string $creado_en
 * @property string $actualizado_en
 */
#[Table(keyType: 'string', incrementing: false)]
final class Negocio extends Model
{
    /** The name of the "created at" column. */
    public const ?string CREATED_AT = 'creado_en';

    /** The name of the "updated at" column. */
    public const ?string UPDATED_AT = 'actualizado_en';

    /** The model's default values for attributes. */
    protected $attributes = [
        'carga_inicial_cerrada' => 0,
        'activo' => 1,
    ];
}
