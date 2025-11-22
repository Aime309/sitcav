<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class SoloAutenticados
{
  static function before()
  {
    if (auth()->user() === null) {
      Flight::redirect('/ingresar');
    } else {
      return auth()->user()->assign(auth()->user()->__get('roles'));
    }
  }
}
