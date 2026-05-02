<?php

declare(strict_types=1);

namespace Leaf {
  if (!function_exists('_env')) {
    /** Gets the value of an environment variable. */
    function _env(string $key, mixed $default = null): mixed
    {
      $env = array_merge(getenv() ?? [], $_ENV ?? []);
      $value = $env[$key] ??= null;

      if ($value === null) {
        return $default;
      }

      switch (strtolower($value)) {
        case 'true':
        case '(true)':
          return true;

        case 'false':
        case '(false)':
          return false;

        case 'empty':
        case '(empty)':
          return '';

        case 'null':
        case '(null)':
          return null;
      }

      if (strpos($value, '"') === 0 && strpos($value, '"') === strlen($value) - 1) {
        return substr($value, 1, -1);
      }

      return $value;
    }
  }
}

namespace {
  function verifyRecaptchaToken(?string $token, ?string $remoteIp = null): array
  {
    $siteKey = trim(strval(\Leaf\_env('RECAPTCHA_SITE_KEY', '')));
    $secretKey = trim(strval(\Leaf\_env('RECAPTCHA_SECRET_KEY', '')));

    if ($siteKey === '' || $secretKey === '') {
      return [
        'success' => true,
        'skipped' => true,
      ];
    }

    if ($token === null || trim($token) === '') {
      return [
        'success' => false,
        'message' => 'Debes completar el reCAPTCHA.',
      ];
    }

    $client = new \GuzzleHttp\Client([
      'base_uri' => 'https://www.google.com',
      'timeout' => 10,
    ]);

    try {
      $response = $client->post('/recaptcha/api/siteverify', [
        'form_params' => [
          'secret' => $secretKey,
          'response' => $token,
          'remoteip' => $remoteIp,
        ],
      ]);
    } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
      error_log("Error al validar reCAPTCHA: {$exception->getMessage()}");

      return [
        'success' => false,
        'message' => 'No se pudo validar el reCAPTCHA.',
      ];
    }

    $payload = json_decode(strval($response->getBody()), true);

    if (!is_array($payload)) {
      error_log('La respuesta de reCAPTCHA no se pudo decodificar.');

      return [
        'success' => false,
        'message' => 'No se pudo validar el reCAPTCHA.',
      ];
    }

    if (($payload['success'] ?? false) !== true) {
      error_log('reCAPTCHA inválido: ' . json_encode($payload));

      return [
        'success' => false,
        'message' => 'La validación de reCAPTCHA falló.',
      ];
    }

    return [
      'success' => true,
      'skipped' => false,
    ];
  }
}
