<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $nombre
 * @property-read float $precio
 */
#[Fillable('nombre', 'precio')]
#[WithoutTimestamps]
final class Producto extends Model {}
