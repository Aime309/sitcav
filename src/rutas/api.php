<?php

use GuzzleHttp\Client;

Flight::group('/api', static function (): void {
  Flight::route('/gemini', static function (): void {
    $peticion = Flight::request();
    $prompt = ($peticion->data->prompt ?: $peticion->query->prompt) ?: 'Hola';
    $claveApi = $_ENV['GEMINI_API_KEY'];

    $gemini = Gemini::factory()
      ->withApiKey($claveApi)
      ->withHttpClient(new Client(['verify' => false]))
      ->make();

    $respuesta = $gemini
      ->generativeModel('gemini-2.0-flash')
      ->generateContent($prompt);

    Flight::halt(200, $respuesta->text());
  });
});
