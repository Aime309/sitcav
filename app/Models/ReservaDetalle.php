<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $cantidad
 */
#[Fillable('cantidad')]
#[Table('reservas_detalles')]
final class ReservaDetalle extends Model {}
