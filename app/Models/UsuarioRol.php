<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read 'administrador'|'encargado'|'vendedor' $rol
 */
#[Fillable('rol')]
#[Table(name: 'usuarios_roles')]
#[WithoutTimestamps]
final class UsuarioRol extends Model {}
