<?php

namespace SITCAV\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
  function sectors(): HasMany
  {
    return $this->hasMany(Sector::class);
  }
}
