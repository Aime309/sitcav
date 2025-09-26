<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Compra;

final readonly class ControladorDeCompras
{
  function listarCompras(): void
  {
    Flight::json(Compra::all());
  }

  function mostrarDetallesDeCompra(int $id)
  {
    $compra = Compra::query()->with('detalles')->find($id);

    if (!$compra) {
      return true;
    }

    Flight::json($compra);
  }
}
