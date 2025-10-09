<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class SoloVisitantes
{
  static function before()
  {
    if (auth()->id() === null) {
      return true;
    } else {
      Flight::redirect('/');
    }
  }
}
