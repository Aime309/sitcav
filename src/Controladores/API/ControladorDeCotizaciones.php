<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Cotizacion;

final readonly class ControladorDeCotizaciones
{
  function listarCotizaciones(): void
  {
    Flight::json(Cotizacion::all());
  }

  function mostrarDetallesDeCotizacion(int $id)
  {
    $cotizacion = Cotizacion::query()->find($id);

    if (!$cotizacion) {
      return true;
    }

    Flight::json($cotizacion);
  }
}
