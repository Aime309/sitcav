<?php

use SITCAV\Controladores\API\ControladorDeClientes;
use SITCAV\Controladores\API\ControladorDePerfil;
use SITCAV\Controladores\API\ControladorDeProductos;

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
  });

  Flight::group('/productos', static function (): void {
    Flight::route('GET /', [ControladorDeProductos::class, 'listarProductos']);
    Flight::route('POST /', [ControladorDeProductos::class, 'registrarProducto']);

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('GET /', [ControladorDeProductos::class, 'mostrarDetallesDelProducto']);
      Flight::route('PATCH /', [ControladorDeProductos::class, 'actualizarProducto']);
      Flight::route('DELETE /', [ControladorDeProductos::class, 'eliminarProducto']);
    });
  });
});
