<?php

namespace SITCAV\Autorizadores;

use Flight;
use SITCAV\Enums\ClaveSesion;

final readonly class SoloContratados
{
  static function before()
  {
    if (!filter_var(auth()->user()?->esta_despedido, FILTER_VALIDATE_BOOL)) {
      return true;
    }

    flash()->set(['Este usuario ha sido despedido.'], ClaveSesion::MENSAJES_ERRORES->name);
    Flight::redirect('/salir');

    exit;
  }
}
