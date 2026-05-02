<?php

return [
  # ======================================================================
  # = Variables de conexión a la base de datos (requeridas por leafs/db) =
  # ======================================================================
  'DB_CONNECTION' => 'sqlite',
  // 'DB_HOST' => '127.0.0.1',
  // 'DB_PORT' => '3306',
  // 'DB_USERNAME' => 'root',
  // 'DB_PASSWORD' => null,
  'DB_DATABASE' => __DIR__ . '/database/database.sqlite',

  'TOKEN_SECRET' => '{token-secret}',
  'ENVIRONMENT' => 'development',
  'TIMEZONE' => 'America/Caracas',

  # ======================================================================================
  # =           Credenciales de Google (https://console.cloud.google.com/auth)           =
  # ======================================================================================
  'GOOGLE_AUTH_CLIENT_ID' => '{google-client-id}',
  'GOOGLE_AUTH_CLIENT_SECRET' => '{google-client-secret}',

  # ==============================================================================================
  # =           Credenciales de Google reCAPTCHA v2 Checkbox (https://www.google.com/recaptcha) =
  # ==============================================================================================
  'RECAPTCHA_SITE_KEY' => '',
  'RECAPTCHA_SECRET_KEY' => '',

  # =========================================================================================
  # =           Credenciales de Gemini (https://aistudio.google.com/app/api-keys)           =
  # =========================================================================================
  'GEMINI_API_KEY' => 'YOUR_API_KEY',

  'PHPMAILER_HOST' => 'smtp.gmail.com',
  'PHPMAILER_USERNAME' => '<usuario>@gmail.com',

  # ============================================================================================================
  # =           Contraseña para PHPMailer, se obtiene de (https://myaccount.google.com/apppasswords)           =
  # ============================================================================================================
  'PHPMAILER_PASSWORD' => '{google-app-password}',

  # ==========================================================================================================================
  # =           Credenciales para la API de consulta de las tasas de cambio https://www.dolarvzla.com/settings/api           =
  # ==========================================================================================================================
  'TASA_BCV_API_KEY' => '{tasa-bcv-api-key}',
];
