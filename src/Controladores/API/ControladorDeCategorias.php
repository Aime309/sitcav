<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Categoria;

final readonly class ControladorDeCategorias
{
  function listarCategorias(): void
  {
    Flight::json(Categoria::all());
  }

  function mostrarDetallesDeLaCategoria(int $id)
  {
    $categoria = Categoria::query()->find($id);

    if (!$categoria) {
      return true;
    }

    Flight::json($categoria);
  }
}
