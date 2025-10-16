<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class SoloVisitantes
{
  static function before()
  {
    if (auth()->user() === null) {
      return true;
    } else {
      Flight::redirect('/');
    }
  }
}
