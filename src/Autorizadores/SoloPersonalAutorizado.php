<?php

namespace SITCAV\Autorizadores;

use Flight;
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

    flash()->set(['No tienes permiso para acceder realizar esta acción.'], 'errores');
    Flight::redirect('/');
  }
}
