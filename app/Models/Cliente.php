<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $nombre
 * @property-read string $apellido
 * @property-read string $correo
 * @property-read string $clave
 * @property-read string $telefono
 */
#[Fillable('nombre', 'apellido', 'correo', 'clave', 'telefono')]
#[WithoutTimestamps]
final class Cliente extends Model {}
