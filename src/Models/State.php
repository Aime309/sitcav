<?php

namespace SITCAV\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
  function locations(): HasMany
  {
    return $this->hasMany(Location::class);
  }
}
