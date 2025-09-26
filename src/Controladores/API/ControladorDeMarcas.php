<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Marca;

final readonly class ControladorDeMarcas
{
  function listarMarcas(): void
  {
    Flight::json(Marca::all());
  }

  function mostrarDetallesDeLaMarca(int $id)
  {
    $marca = Marca::query()->find($id);

    if (!$marca) {
      return true;
    }

    Flight::json($marca);
  }
}
