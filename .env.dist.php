<?php

return [
  # ========================================================================================================
  # = Variables de conexión a la base de datos (requeridas por illuminate/database, leafs/db y leafs/auth) =
  # ========================================================================================================
  'DB_CONNECTION' => 'sqlite',
  // 'DB_HOST' => '127.0.0.1',
  // 'DB_PORT' => '3306',
  // 'DB_USERNAME' => 'root',
  // 'DB_PASSWORD' => null,
  'DB_DATABASE' => __DIR__ . '/bd/sitcav.db',

  'TEST_URL' => 'http://localhost:8080',
  'TOKEN_SECRET' => '{token-secret}',
  'ENVIRONMENT' => 'development',

  # ======================================================================================
  # =           Credenciales de Google (https://console.cloud.google.com/auth)           =
  # ======================================================================================
  'GOOGLE_AUTH_CLIENT_ID' => '{google-client-id}',
  'GOOGLE_AUTH_CLIENT_SECRET' => '{google-client-secret}',

  'GEMINI_API_KEY' => 'YOUR_API_KEY',
];
