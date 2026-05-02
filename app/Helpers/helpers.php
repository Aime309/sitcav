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
  function normalizeSpanishName(string $value): string
  {
    $value = preg_replace('/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/u', '', $value) ?? '';
    $value = preg_replace('/\s+/u', ' ', $value) ?? '';

    return trim($value);
  }

  function spanishNamePattern(): string
  {
    return '/^[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?: [A-Za-zÁÉÍÓÚáéíóúÑñ]+)*$/u';
  }

  function spanishNameMessage(string $fieldLabel): string
  {
    return "El campo {$fieldLabel} solo puede contener letras, espacios, la letra ñ y vocales con tilde.";
  }

  function passwordPolicyPattern(): string
  {
    return '/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
  }

  function passwordPolicyMessage(): string
  {
    return 'La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo.';
  }

  function normalizeSecretAnswer(string $value): string
  {
    $value = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

    return mb_strtolower($value, 'UTF-8');
  }

  function verifyStoredSecretAnswer(string $plainAnswer, ?string $storedAnswer, mixed $passwordVerify = null): bool
  {
    $normalizedPlainAnswer = normalizeSecretAnswer($plainAnswer);
    $storedAnswer = trim(strval($storedAnswer ?? ''));

    if ($normalizedPlainAnswer === '' || $storedAnswer === '') {
      return false;
    }

    if ((password_get_info($storedAnswer)['algo'] ?? 0) !== 0) {
      if (is_callable($passwordVerify)) {
        return boolval(call_user_func($passwordVerify, $normalizedPlainAnswer, $storedAnswer));
      }

      return password_verify($normalizedPlainAnswer, $storedAnswer);
    }

    return normalizeSecretAnswer($storedAnswer) === $normalizedPlainAnswer;
  }

  function validateAdminRegisterPayload(object $data): array
  {
    $names = normalizeSpanishName(strval($data->names ?? ''));
    $lastnames = normalizeSpanishName(strval($data->lastnames ?? ''));
    $email = trim(strval($data->email ?? ''));
    $password = strval($data->contrasena ?? '');
    $secretQuestion = trim(strval($data->secret_question ?? ''));
    $secretAnswer = trim(strval($data->secret_answer ?? ''));

    if ($names === '') {
      return ['success' => false, 'message' => 'Debes ingresar los nombres.'];
    }

    if ($lastnames === '') {
      return ['success' => false, 'message' => 'Debes ingresar los apellidos.'];
    }

    if (!preg_match(spanishNamePattern(), $names)) {
      return ['success' => false, 'message' => spanishNameMessage('Nombres')];
    }

    if (!preg_match(spanishNamePattern(), $lastnames)) {
      return ['success' => false, 'message' => spanishNameMessage('Apellidos')];
    }

    if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      return ['success' => false, 'message' => 'Debes ingresar un correo electrónico válido.'];
    }

    if (!preg_match(passwordPolicyPattern(), $password)) {
      return ['success' => false, 'message' => passwordPolicyMessage()];
    }

    if ($secretQuestion === '') {
      return ['success' => false, 'message' => 'Debes seleccionar una pregunta secreta.'];
    }

    if ($secretAnswer === '') {
      return ['success' => false, 'message' => 'Debes ingresar la respuesta de la pregunta secreta.'];
    }

    return ['success' => true];
  }

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

    $client = new \GuzzleHttp\Client(recaptchaHttpClientOptions());

    try {
      $response = $client->post('/recaptcha/api/siteverify', [
        'http_errors' => false,
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

  function recaptchaSiteKey(): string
  {
    return trim(strval(\Leaf\_env('RECAPTCHA_SITE_KEY', '')));
  }

  function recaptchaSecretKey(): string
  {
    return trim(strval(\Leaf\_env('RECAPTCHA_SECRET_KEY', '')));
  }

  function recaptchaHttpClientOptions(): array
  {
    $options = [
      'base_uri' => 'https://www.google.com',
      'timeout' => 10,
    ];

    $caBundleCandidates = [
      'C:\\xampp\\apache\\bin\\curl-ca-bundle.crt',
      'C:\\xampp\\php\\extras\\ssl\\cacert.pem',
      'C:\\xampp\\php\\cacert.pem',
    ];

    foreach ($caBundleCandidates as $caBundlePath) {
      if (is_file($caBundlePath)) {
        $options['verify'] = $caBundlePath;
        break;
      }
    }

    return $options;
  }

  function syncUsersTableSchema(\PDO $pdo): void
  {
    $columns = $pdo
      ->query('PRAGMA table_info(users)')
      ?->fetchAll(\PDO::FETCH_COLUMN, 1);

    if (!is_array($columns) || $columns === []) {
      return;
    }

    $requiredColumns = [
      'secret_question' => 'VARCHAR(255)',
      'secret_answer' => 'VARCHAR(255)',
    ];

    foreach ($requiredColumns as $columnName => $columnDefinition) {
      if (in_array($columnName, $columns, true)) {
        continue;
      }

      $pdo->exec("ALTER TABLE users ADD COLUMN {$columnName} {$columnDefinition}");
    }
  }

  function isRecaptchaEnabled(): bool
  {
    return recaptchaSiteKey() !== '' && recaptchaSecretKey() !== '';
  }
}
