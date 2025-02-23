<?php

use SITCAV\Autorizadores\GarantizaQueElUsuarioEstaAutenticado;
use SITCAV\Controladores\API\ControladorDeClientes;
use SITCAV\Controladores\API\ControladorDePerfil;

Flight::group('/api', function (): void {
  Flight::route('GET /perfil', [ControladorDePerfil::class, 'obtenerPerfil']);
  Flight::route('POST /registrarse', [ControladorDePerfil::class, 'procesarRegistro']);

  Flight::group('/clientes', static function (): void {
    Flight::route('GET /', [ControladorDeClientes::class, 'listarClientes']);
    Flight::route('POST /', [ControladorDeClientes::class, 'registrarCliente']);

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('PATCH /', [ControladorDeClientes::class, 'actualizarCliente']);
      Flight::route('DELETE /', [ControladorDeClientes::class, 'eliminarCliente']);
      Flight::route('GET /', [ControladorDeClientes::class, 'mostrarDetallesDelCliente']);
    });
  }, [GarantizaQueElUsuarioEstaAutenticado::class]);
});
