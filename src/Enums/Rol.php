<?php

namespace SITCAV\Enums;

enum Rol: string
{
  case VENDEDOR = 'Vendedor';
  case EMPLEADO_SUPERIOR = 'Empleado superior';
  case ENCARGADO = 'Encargado';

  static function comoJsonString(): string
  {
    return json_encode(array_map(
      static fn(Rol $rol) => $rol->value,
      self::cases()
    ));
  }
}
