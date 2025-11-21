<?php

namespace SITCAV\Autorizadores;

use Flight;
use SITCAV\Enums\ClaveSesion;
use SITCAV\Enums\Permiso;

final readonly class SoloPersonalAutorizado
{
  private array $permisos;

  function __construct(Permiso ...$permisos)
  {
    $this->permisos = $permisos;
  }

  function before()
  {
    $permisos = array_map(
      static fn(Permiso $permiso): string => $permiso->name,
      $this->permisos
    );

    if (auth()->user()?->can($permisos)) {
      return true;
    }

    session()->set(ClaveSesion::MENSAJES_ERRORES->name, ['No tienes permiso para acceder realizar esta acción.']);
    Flight::redirect(Flight::request()->referrer);
  }
}
