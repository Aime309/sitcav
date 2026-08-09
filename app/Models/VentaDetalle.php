<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $cantidad
 * @property-read float $precio
 */
#[Fillable('cantidad', 'precio')]
#[Table(name: 'ventas_detalles')]
final class VentaDetalle extends Model {}
