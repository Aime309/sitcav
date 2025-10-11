<?php

use GuzzleHttp\Client;
use SITCAV\Controladores\API\ControladorDeCategorias;
use SITCAV\Controladores\API\ControladorDeClientes;
use SITCAV\Controladores\API\ControladorDeCompras;
use SITCAV\Controladores\API\ControladorDeCotizaciones;
use SITCAV\Controladores\API\ControladorDeEstados;
use SITCAV\Controladores\API\ControladorDeLocalidades;
use SITCAV\Controladores\API\ControladorDeMarcas;
use SITCAV\Controladores\API\ControladorDeNegocios;
use SITCAV\Controladores\API\ControladorDePerfil;
use SITCAV\Controladores\API\ControladorDeProductos;
use SITCAV\Controladores\API\ControladorDeVentas;

Flight::group('/api', static function (): void {
  Flight::route('/gemini', static function (): void {
    $prompt = (Flight::request()->data->prompt ?: Flight::request()->query->prompt) ?: 'Hola';
    $apiKey = $_ENV['GEMINI_API_KEY'];

    $geminiClient = Gemini::factory()
      ->withApiKey($apiKey)
      ->withHttpClient(new Client([
        'verify' => false,
      ]))
      ->make();

    $response = $geminiClient
      ->generativeModel('gemini-2.0-flash')
      ->generateContent($prompt);

    // echo $response->text(); // Hello! How can I assist you today?
    Flight::halt(200, $response->text());

    // Helper method usage
    // $response = $geminiClient->generativeModel(
    //     model: GeminiHelper::generateGeminiModel(
    //         variation: ModelVariation::FLASH,
    //         generation: 2.5,
    //         version: "preview-04-17"
    //     ), // models/gemini-2.5-flash-preview-04-17
    // );
    // $response->text(); // Hello! How can I assist you today?
  });

  Flight::route('GET /perfil', [ControladorDePerfil::class, 'obtenerPerfil']);
  Flight::route('POST /registrarse', [ControladorDePerfil::class, 'procesarRegistro']);

  Flight::group('/categorias', static function (): void {
    Flight::route('GET /', [ControladorDeCategorias::class, 'listarCategorias']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeCategorias::class, 'mostrarDetallesDeLaCategoria']);
  });

  Flight::group('/compras', static function (): void {
    Flight::route('GET /', [ControladorDeCompras::class, 'listarCompras']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeCompras::class, 'mostrarDetallesDeCompra']);
  });

  Flight::group('/clientes', static function (): void {
    Flight::route('GET /', [ControladorDeClientes::class, 'listarClientes']);
    Flight::route('POST /', [ControladorDeClientes::class, 'registrarCliente']);

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('PATCH /', [ControladorDeClientes::class, 'actualizarCliente']);
      Flight::route('DELETE /', [ControladorDeClientes::class, 'eliminarCliente']);
      Flight::route('GET /', [ControladorDeClientes::class, 'mostrarDetallesDelCliente']);
    });
  });

  Flight::group('/cotizaciones', static function (): void {
    Flight::route('GET /', [ControladorDeCotizaciones::class, 'listarCotizaciones']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeCotizaciones::class, 'mostrarDetallesDeCotizacion']);
  });

  Flight::group('/estados', static function (): void {
    Flight::route('GET /', [ControladorDeEstados::class, 'listarEstados']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeEstados::class, 'mostrarDetallesDelEstado']);
  });

  Flight::group('/localidades', static function (): void {
    Flight::route('GET /', [ControladorDeLocalidades::class, 'listarLocalidades']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeLocalidades::class, 'mostrarDetallesDeLaLocalidad']);
  });

  Flight::group('/marcas', static function (): void {
    Flight::route('GET /', [ControladorDeMarcas::class, 'listarMarcas']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeMarcas::class, 'mostrarDetallesDeLaMarca']);
  });

  Flight::group('/negocios', static function (): void {
    Flight::route('GET /', [ControladorDeNegocios::class, 'listarNegocios']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeNegocios::class, 'mostrarDetallesDeNegocio']);
  });

  Flight::group('/ventas', static function (): void {
    Flight::route('GET /', [ControladorDeVentas::class, 'listarVentas']);
    Flight::route('GET /@id:[0-9]+', [ControladorDeVentas::class, 'mostrarDetallesDeVenta']);
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
