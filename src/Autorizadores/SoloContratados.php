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

    session()->set(ClaveSesion::MENSAJES_ERRORES->name, ['Este usuario ha sido despedido.']);
    Flight::redirect('/salir');
  }
}
