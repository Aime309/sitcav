<?php

namespace SITCAV\Controladores\Web;

use Flight;

final readonly class ControladorDeSesion
{
  function procesarIngreso(): void
  {
    $credenciales = Flight::request()->data->getData();
    $fueAutenticadoExitosamente = auth()->login($credenciales);

    if ($fueAutenticadoExitosamente) {
      Flight::redirect('/panel');
    } else {
      dd(auth()->errors());
    }
  }

  function cerrarSesion(): void
  {
    auth()->logout();
    Flight::redirect('/');
  }
}
