<?php

namespace SITCAV\Modelos;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Throwable;

/**
 * @property-read ?Localidad $localidad
 * @property-read ?Collection<int, Venta> $ventas
 */
final class Cliente extends Model
{
  protected $table = 'clientes';

  private const PATRONES = [
    'nombres' => '/^[a-zA-ZáéíóúñÁÉÍÓÚÑ]{2,}\s?[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/',
  ];

  public $timestamps = false;

  function localidad(): BelongsTo
  {
    return $this->belongsTo(Localidad::class, 'id_localidad');
  }

  function sector(): BelongsTo
  {
    return $this->belongsTo(Sector::class, 'id_sector');
  }

  function ventas(): HasMany
  {
    return $this->hasMany(Venta::class, 'id_cliente');
  }

  function __set($key, $value) {
    switch ($key) {
      case 'cedula':
        if ($value < 0) {
          throw new Exception('La cédula no puede ser negativa');
        }

        break;
      case 'nombres':
      case 'apellidos':
        if (!preg_match(self::PATRONES['nombres'], $value)) {
          throw new Exception('Los nombres no son válidos (solo letras y espacios)');
        }

        break;
      case 'telefono':
        try {
          $telefono = PhoneNumberUtil::getInstance()->parse($value);

          $value = PhoneNumberUtil::getInstance()->format(
            $telefono,
            PhoneNumberFormat::INTERNATIONAL
          );
        } catch (Throwable) {
          throw new Exception('El teléfono no es válido');
        }

        break;
    }

    parent::__set($key, $value);
  }
}
