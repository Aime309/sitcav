<?php

use GuzzleHttp\Client;

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

  Flight::group('/ajustes', static function (): void {
    Flight::route('POST /tema', static function (): void {
      $tema = Flight::request()->data->tema ?: session()->get('tema', 'matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"');
      $temaColores = Flight::request()->data->tema_colores ?: session()->get('tema_colores', 'Blue_Theme');
      $direccion = Flight::request()->data->direccion ?: session()->get('direccion', 'ltr');
      $layout = Flight::request()->data->layout ?: session()->get('layout', 'vertical');
      $container = Flight::request()->data->container ?: session()->get('container', 'boxed');
      $tipoMenu = Flight::request()->data->tipo_menu ?: session()->get('sidebar_type', 'full');
      $tipoTarjeta = Flight::request()->data->tipo_tarjeta ?: session()->get('card_type', 'border');

      session()->set('tema', $tema);
      session()->set('tema_colores', $temaColores);
      session()->set('direccion', $direccion);
      session()->set('layout', $layout);
      session()->set('container', $container);
      session()->set('sidebar_type', $tipoMenu);
      session()->set('card_type', $tipoTarjeta);
    });
  });
});
