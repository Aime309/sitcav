<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $imagen
 */
#[Table(name: 'negocios_imagenes')]
#[WithoutTimestamps]
#[Fillable('imagen')]
final class NegocioImagen extends Model {}
