<?php

namespace SITCAV\Controladores\API;

use Flight;
use Leaf\Helpers\Password;
use Throwable;

final readonly class ControladorDePerfil
{
  function obtenerPerfil(): void
  {
    Flight::json([
      'id' => auth()->id(),
      'cedula' => auth()->user()?->cedula
    ]);
  }

  function procesarRegistro(): void
  {
    $datosDelFormularioDeRegistro = Flight::request()->data;

    try {
      auth()->register([
        'cedula' => $datosDelFormularioDeRegistro->cedula,
        'clave' => Password::hash($datosDelFormularioDeRegistro->clave),
        'rol' => 'Administrador',
        'pregunta_secreta' => $datosDelFormularioDeRegistro->pregunta_secreta,
        'respuesta_secreta' => Password::hash($datosDelFormularioDeRegistro->respuesta_secreta),
      ]);

      auth()->login([
        'cedula' => $datosDelFormularioDeRegistro->cedula,
        'clave' => $datosDelFormularioDeRegistro->clave
      ]);
    } catch (Throwable $exception) {
      http_response_code(409);

      if (str_contains($exception->getMessage(), 'usuarios.cedula')) {
        exit('La cédula ya existe');
      }
    }
  }
}
