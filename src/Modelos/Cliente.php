<?php

namespace SITCAV\Modelos;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Throwable;

/**
 * @property-read int $id
 * @property int $cedula
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono
 * @property Localidad $localidad
 * @property Sector $sector
 * @property-read Usuario $usuario
 * @property-read string $nombreCompleto
 * @property-read float $deudaAcumuladaDolares
 * @property-read Venta[] $compras
 */
final class Cliente extends Model
{
  protected $table = 'clientes';
  protected $with = ['localidad', 'sector', 'compras'];
  protected $hidden = ['id_localidad', 'id_sector'];

  private const PATRONES = [
    'nombres' => '/^[a-zA-ZáéíóúñÁÉÍÓÚÑ]{1,}\s?[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+$/',
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

  function compras(): HasMany
  {
    return $this->hasMany(Venta::class, 'id_cliente');
  }

  function getNombreCompletoAttribute(): string
  {
    return "{$this->nombres} {$this->apellidos}";
  }

  function getUsuarioAttribute(): Usuario
  {
    return $this->localidad->estado->usuario;
  }

  function getDeudaAcumuladaDolaresAttribute(): float
  {
    $deudaAcumuladaDolares = 0;

    foreach ($this->compras as $compra) {
      foreach ($compra->detalles as $detalle) {
        $subTotalDolares = $detalle->precioUnitarioFijoDolares * $detalle->cantidad;
        $pagosAcumulados = 0;

        foreach ($detalle->pagos as $pago) {
          $pagosAcumulados += $pago->monto;
        }

        $deudaAcumuladaDolares += $subTotalDolares - $pagosAcumulados;
      }
    }

    return $deudaAcumuladaDolares;
  }

  function __set($key, $value)
  {
    switch ($key) {
      case 'id':
        throw new Error('Cannot modify property ' . $this::class . "::$$key");
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

        $value = str_replace('  ', ' ', $value);
        $value = mb_convert_case($value, MB_CASE_TITLE);

        $preposiciones = ['De' => 'de', 'La' => 'la'];
        $conjunciones = ['Y' => 'y'];

        $value = str_replace(
          array_keys($preposiciones + $conjunciones),
          array_values($preposiciones + $conjunciones),
          $value
        );

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
