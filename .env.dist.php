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
];
