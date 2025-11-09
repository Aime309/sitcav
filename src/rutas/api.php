<?php

use GuzzleHttp\Client;
use SITCAV\Enums\ClaveSesion;

Flight::group('/api', static function (): void {
  Flight::route('/gemini', static function (): void {
    $prompt = (Flight::request()->data->prompt ?: Flight::request()->query->prompt) ?: 'Hola';
    $apiKey = $_ENV['GEMINI_API_KEY'];

    $geminiClient = Gemini::factory()
      ->withApiKey($apiKey)
      ->withHttpClient(new Client(['verify' => false]))
      ->make();

    $response = $geminiClient
      ->generativeModel('gemini-2.0-flash')
      ->generateContent($prompt);

    Flight::halt(200, $response->text());
  });

  Flight::group('/ajustes', static function (): void {
    Flight::route('POST /tema', static function (): void {
      $tema = Flight::request()->data->tema ?: session()->get(ClaveSesion::UI_TEMA->name, 'light');
      $temaColores = Flight::request()->data->tema_colores ?: session()->get(ClaveSesion::UI_COLORES->name, 'Blue_Theme');
      $direccion = Flight::request()->data->direccion ?: session()->get(ClaveSesion::UI_DIRECCION->name, 'ltr');
      $layout = Flight::request()->data->layout ?: session()->get(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, 'vertical');
      $container = Flight::request()->data->container ?: session()->get(ClaveSesion::UI_ANCHURA->name, 'boxed');
      $tipoMenu = Flight::request()->data->tipo_menu ?: session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'full');
      $tipoTarjeta = Flight::request()->data->tipo_tarjeta ?: session()->get(ClaveSesion::UI_TIPO_TARJETAS->name, 'border');

      session()->set(ClaveSesion::UI_TEMA->name, $tema);
      session()->set(ClaveSesion::UI_COLORES->name, $temaColores);
      session()->set(ClaveSesion::UI_DIRECCION->name, $direccion);
      session()->set(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, $layout);
      session()->set(ClaveSesion::UI_ANCHURA->name, $container);
      session()->set(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, $tipoMenu);
      session()->set(ClaveSesion::UI_TIPO_TARJETAS->name, $tipoTarjeta);
    });
  });
});
