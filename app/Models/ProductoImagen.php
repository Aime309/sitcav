<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $producto_id
 * @property string $imagen
 * @property string $creado_en
 * @property string $actualizado_en
 */
#[Table(name: 'productos_imagenes', keyType: 'string', incrementing: false)]
#[Fillable('id', 'imagen')]
final class ProductoImagen extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [];
}
