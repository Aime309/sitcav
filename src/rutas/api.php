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
      $tema = Flight::request()->data->tema;

      session()->set('tema', $tema);
    });
  });
});
