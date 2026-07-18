<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $nombre
 * @property string $apellido
 * @property string $correo
 * @property string $clave
 * @property string $telefono
 */
#[WithoutTimestamps]
#[Fillable('nombre', 'apellido', 'correo', 'clave', 'telefono')]
final class Cliente extends Model {}
