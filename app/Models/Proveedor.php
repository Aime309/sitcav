<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Override;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property-read string $nombre
 */
#[Fillable('slug', 'nombre')]
#[Table(name: 'proveedores', key: 'slug', keyType: 'string')]
#[WithoutIncrementing]
#[WithoutTimestamps]
final class Proveedor extends Model
{
    public $usesUniqueIds = true;

    #[Override]
    public function newUniqueId()
    {
        return Str::slug($this->nombre);
    }

    #[Override]
    public function uniqueIds()
    {
        return ['slug'];
    }
}
