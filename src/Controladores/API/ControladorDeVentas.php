<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Venta;

final readonly class ControladorDeVentas
{
  function listarVentas(): void
  {
    Flight::json(Venta::all());
  }

  function mostrarDetallesDeVenta(int $id)
  {
    $venta = Venta::query()->with('detalles')->find($id);

    if (!$venta) {
      return true;
    }

    Flight::json($venta);
  }
}
