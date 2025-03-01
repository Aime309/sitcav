<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class GarantizaQueElUsuarioEstaAutenticado
{
  static function before()
  {
    if (auth()->id() === null) {
      Flight::redirect('/ingresar');
    } else {
      return true;
    }
  }
}
