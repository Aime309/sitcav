<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class ModoMantenimiento
{
  static function before(): void {
    Flight::render('paginas/mantenimiento');

    exit;
  }
}
