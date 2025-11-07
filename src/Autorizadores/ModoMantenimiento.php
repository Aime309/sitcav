<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class ModoMantenimiento
{
  static function before(): void {
    Flight::render('paginas/mantenimiento', [], 'pagina');

    Flight::render('diseños/materialm-para-errores', [
      'titulo' => 'Modo de Mantenimiento',
    ]);

    exit;
  }
}
