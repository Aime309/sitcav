<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class SoloPersonalAutorizado
{
  function __construct(private array $permisos)
  {
    // ...
  }

  function before()
  {
    if (auth()->user()?->can($this->permisos)) {
      return true;
    }

    flash()->set(['No tienes permiso para acceder a esta sección.'], 'errores');
    Flight::redirect('/');
  }
}
