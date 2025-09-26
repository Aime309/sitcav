<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Localidad;

final readonly class ControladorDeLocalidades
{
  function listarLocalidades(): void
  {
    Flight::json(Localidad::all());
  }

  function mostrarDetallesDeLaLocalidad(int $id)
  {
    $localidad = Localidad::query()->find($id);

    if (!$localidad) {
      return true;
    }

    Flight::json($localidad);
  }
}
