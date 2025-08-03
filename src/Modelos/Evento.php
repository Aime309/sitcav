<?php

namespace SITCAV\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read Carbon $fecha_hora_creacion
 * @property-read 'Contrato'|'Despido'|'Ascenso' $tipo
 * @property-read Usuario $entidad
 * @property-read Usuario $encargado
 */
final class Evento extends Model
{
  protected $table = 'eventos';
  public $timestamps = false;

  protected $casts = [
    'fecha_hora_creacion' => 'datetime',
  ];

  function encargado(): BelongsTo
  {
    return $this->belongsTo(Usuario::class, 'id_encargado');
  }

  function __get($key)
  {
    switch ($key) {
      case 'entidad':
        static $entidad = null;

        if (!$entidad) {
          switch ($this->attributes['tabla']) {
            case 'usuarios':
              $entidad = Usuario::query()->find($this->attributes['id_entidad']);
              break;
          }
        }

        return $entidad;
    }

    return parent::__get($key);
  }
}
