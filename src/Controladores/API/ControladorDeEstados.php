<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Estado;

final readonly class ControladorDeEstados
{
  function listarEstados(): void
  {
    Flight::json(Estado::all());
  }

  function mostrarDetallesDelEstado(int $id)
  {
    $estado = Estado::query()->find($id);

    if (!$estado) {
      return true;
    }

    Flight::json($estado);
  }
}
