<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Cliente;
use SITCAV\Modelos\Localidad;
use SITCAV\Modelos\Sector;
use Throwable;

final readonly class ControladorDeClientes
{
  static function listarClientes(): void
  {
    $clientes = Cliente::with(['localidad', 'sector', 'ventas'])->get();

    Flight::json($clientes);
  }

  static function registrarCliente(): void
  {
    $datos = Flight::request()->data;
    $error = '';

    if (!$datos->cedula) {
      $error = 'La cédula es requerida';
    } elseif (!$datos->nombres) {
      $error = 'Los nombres son requeridos';
    } elseif (!$datos->apellidos) {
      $error = 'Los apellidos son requeridos';
    } elseif (!$datos->telefono) {
      $error = 'El teléfono es requerido';
    } elseif (!$datos->id_localidad) {
      $error = 'La localidad es requerida';
    }

    try {
      Localidad::query()->findOrFail($datos->id_localidad);
    } catch (Throwable) {
      $error = 'La localidad no existe';
    }

    if ($datos->id_sector) {
      $sector = Sector::with('localidad')->find($datos->id_sector);

      if (!$sector) {
        $error = 'El sector no existe';
      } elseif ($sector->localidad->id !== $datos->id_localidad) {
        $error = 'El sector no pertenece a la localidad seleccionada';
      }
    }

    if ($error) {
      Flight::halt(400, $error);
    }

    try {
      $cliente = new Cliente;
      $cliente->cedula = $datos->cedula;
      $cliente->nombres = $datos->nombres;
      $cliente->apellidos = $datos->apellidos;
      $cliente->telefono = $datos->telefono;
      $cliente->id_localidad = $datos->id_localidad;
      $cliente->id_sector = $datos->id_sector;
      $cliente->save();

      Flight::json($cliente, 201);
    } catch (Throwable $error) {
      Flight::halt(409, match (true) {
        str_contains($error->getMessage(), 'cedula') => 'La cédula ya está registrada',
        default => $error->getMessage()
      });
    }
  }

  static function actualizarCliente(int $id): void {
    $cliente = Cliente::with('localidad')->find($id);
    $datos = Flight::request()->data;
    $error = '';

    if (!$cliente) {
      Flight::halt(404, 'El cliente no existe');

      exit;
    }

    if ($datos->id_localidad) {
      try {
        Localidad::query()->findOrFail($datos->id_localidad);
      } catch (Throwable) {
        $error = 'La localidad no existe';
      }
    }

    if ($datos->id_sector) {
      $sector = Sector::with('localidad')->find($datos->id_sector);

      if (!$sector) {
        $error = 'El sector no existe';
      } elseif ($sector->localidad->id !== ($datos->id_localidad ?? $cliente->localidad?->id)) {
        $error = 'El sector no pertenece a la localidad seleccionada';
      }
    }

    if ($error) {
      Flight::halt(400, $error);
    }

    try {
      if ($datos->cedula) {
        $cliente->cedula = $datos->cedula;
      }

      if ($datos->nombres) {
        $cliente->nombres = $datos->nombres;
      }

      if ($datos->apellidos) {
        $cliente->apellidos = $datos->apellidos;
      }

      if ($datos->telefono) {
        $cliente->telefono = $datos->telefono;
      }

      if ($datos->id_localidad) {
        $cliente->id_localidad = $datos->id_localidad;
      }

      if ($datos->id_sector) {
        $cliente->id_sector = $datos->id_sector;
      }

      $cliente->save();

      Flight::json($cliente);
    } catch (Throwable $error) {
      Flight::halt(409, $error->getMessage());
    }
  }

  static function eliminarCliente(int $id): void {
    $cliente = Cliente::with('ventas')->find($id);

    if (!$cliente) {
      Flight::halt(404, 'El cliente no existe');

      exit;
    }

    if ($cliente->ventas->isNotEmpty()) {
      Flight::halt(409, 'El cliente tiene ventas asociadas');
    }

    $cliente->delete();
    Flight::json($cliente);
  }
}
