<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
      ->generativeModel('gemini-3-flash-preview')
      ->generateContent($prompt);

    echo $respuesta->text();
  });

  Flight::route('/bcv/exchange-rate', static function (): void {
    try {
      $dolaresDeLaApi = (new Client())->get('https://api.dolarvzla.com/public/bcv/exchange-rate', [
        'headers' => [
          'x-dolarvzla-key' => $_ENV['TASA_BCV_API_KEY'],
        ],
      ])->getBody()->getContents();

      Flight::json(json_decode($dolaresDeLaApi));
    } catch (GuzzleException $error) {
      error_log($error);

      Flight::halt(503);
    }
  });
});
