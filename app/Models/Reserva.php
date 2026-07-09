<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $negocio_id
 * @property string $cliente_id
 * @property '' $estado
 * @property string $expira_en
 * @property string $creado_en
 * @property string $actualizado_en
 */
#[Table(keyType: 'string', incrementing: false)]
final class Reserva extends Model
{
    public const ?string CREATED_AT = 'creado_en';
    public const ?string UPDATED_AT = 'actualizado_en';

    protected $attributes = [];
}
