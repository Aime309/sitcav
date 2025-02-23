<?php

namespace SITCAV\Controladores\Web;

use Flight;

final readonly class ControladorDeSesion
{
  static function procesarIngreso(): void
  {
    $credenciales = Flight::request()->data;

    $error = match (true) {
      !$credenciales->cedula => 'La çédula es requerida',
      !$credenciales->clave => 'La contraseña es requerida',
      default => ''
    };

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
