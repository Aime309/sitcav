<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Client;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class FeatureTestCase extends TestCase
{
  protected static Client $client;

  public function setUp(): void
  {
    parent::setUp();

    $_ENV['APP_URL'] ??= 'http://localhost:5000';

    if (!str_ends_with(strval($_ENV['APP_URL']), '/')) {
      $_ENV['APP_URL'] .= '/';
    }

    self::$client = new Client([
      'base_uri' => $_ENV['APP_URL'],
    ]);

    $pdo = new PDO('sqlite:' . dirname(__DIR__, 2) . '/instance/system_data.db');
    $pdo->exec("DELETE FROM usuarios WHERE cedula = '28072391'");
  }
}
