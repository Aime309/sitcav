<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Negocio;

final readonly class ControladorDeNegocios
{
  function listarNegocios(): void
  {
    Flight::json(Negocio::all());
  }

  function mostrarDetallesDeNegocio(int $id)
  {
    $negocio = Negocio::query()->find($id);

    if (!$negocio) {
      return true;
    }

    Flight::json($negocio);
  }
}
