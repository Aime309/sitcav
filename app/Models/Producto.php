<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $negocio_id
 * @property string $nombre
 * @property string $descripcion
 * @property float $precio
 * @property string $imagenes
 * @property int $activo
 * @property string $creado_end
 * @property string $actualizado_en
 */
#[Table(keyType: 'string', incrementing: false)]
final class Producto extends Model
{
    /** The name of the "created at" column. */
    public const ?string CREATED_AT = 'creado_en';

    /** The name of the "updated at" column. */
    public const ?string UPDATED_AT = 'actualizado_en';

    /** The model's default values for attributes. */
    protected $attributes = [
        'activo' => 1,
        'imagenes' => '[]',
    ];
}
