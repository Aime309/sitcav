<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read 'activa' $estado
 * @property-read string $expira_en
 */
#[Fillable('estado', 'expira_en')]
final class Reserva extends Model {}
