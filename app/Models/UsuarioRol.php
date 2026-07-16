<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property 'administrador'|'encargado'|'vendedor' $rol
 */
#[Table(name: 'usuarios_roles')]
#[WithoutTimestamps]
#[Fillable('rol')]
final class UsuarioRol extends Model {}
