<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\Cliente;
use SITCAV\Modelos\Localidad;
use SITCAV\Modelos\Sector;
use SITCAV\Modelos\UsuarioAutenticado;
use Throwable;

final readonly class ControladorDeClientes
{
  function __construct(private UsuarioAutenticado $usuarioAutenticado) {}

  function listarClientes(): void
  {
    $clientes = Cliente::with(['localidad', 'sector', 'ventas', 'localidad.estado.usuario'])->get();
    $clientes = $clientes->filter(fn(Cliente $cliente): bool => $cliente->usuario->is($this->usuarioAutenticado) || $cliente->usuario->is($this->usuarioAutenticado->administrador));

    Flight::json($clientes);
  }

  static function mostrarDetallesDelCliente(int $id): void
  {
    try {
      $cliente = Cliente::with([
        'localidad',
        'sector',
        'ventas',
        'localidad.estado.usuario',
        'ventas.detalles',
        'ventas.detalles.producto',
        'ventas.detalles.pagos',
        'ventas.detalles.pagos.tipo'
      ])
        ->findOrFail($id);

      $cliente->usuario = $cliente->localidad->estado->usuario;
      $cliente->deuda_acumulada = $cliente->getDeudaAcumulada();

      Flight::json($cliente);
    } catch (Throwable) {
      Flight::halt(404, 'El cliente no existe');
    }
  }

  static function registrarCliente(): void
  {
    $datos = Flight::request()->data;

    $error = match (true) {
      !$datos->cedula => 'La cédula es requerida',
      !$datos->nombres => 'Los nombres son requeridos',
      !$datos->apellidos => 'Los apellidos son requeridos',
      !$datos->telefono => 'El teléfono es requerido',
      !$datos->id_localidad => 'La localidad es requerida',
      default => ''
    };

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

  static function actualizarCliente(int $id): void
  {
    $datos = Flight::request()->data;
    $error = '';

    try {
      $cliente = Cliente::with('localidad')->findOrFail($id);
    } catch (Throwable) {
      Flight::halt(404, 'El cliente no existe');
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

  static function eliminarCliente(int $id): void
  {
    try {
      $cliente = Cliente::with('ventas')->findOrFail($id);
    } catch (Throwable) {
      Flight::halt(404, 'El cliente no existe');
    }

    if ($cliente->ventas->isNotEmpty()) {
      Flight::halt(409, 'El cliente tiene ventas registradas');
    }

    $cliente->delete();
    Flight::json($cliente);
  }
}
