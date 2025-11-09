<?php

use GuzzleHttp\Client;

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
});
