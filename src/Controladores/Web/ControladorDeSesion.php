<?php

namespace SITCAV\Controladores\Web;

use Flight;

final readonly class ControladorDeSesion
{
  static function procesarIngreso(): void
  {
    $credenciales = Flight::request()->data;

    $error = '';

    if (!$credenciales->cedula) {
      $error = 'No se envió la cédula';
    } elseif (!$credenciales->clave) {
      $error = 'No se envió la contraseña';
    }

    if ($error) {
      Flight::halt(400, $error);
    }

    $fueAutenticadoExitosamente = auth()->login($credenciales->getData());

    if ($fueAutenticadoExitosamente) {
      Flight::redirect('/panel');
    } else {
      Flight::halt(
        code: 401,
        message: auth()->errors()['auth']
          ?? auth()->errors()['password']
          ?? 'Cédula o contraseña incorrecta'
      );
    }
  }

  static function cerrarSesion(): void
  {
    auth()->logout();
    Flight::redirect('/');
  }
}
