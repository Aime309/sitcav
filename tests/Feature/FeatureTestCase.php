<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class FeatureTestCase extends TestCase
{
  protected static Client $client;

  public function setUp(): void
  {
    parent::setUp();

    $_ENV['APP_URL'] ??= 'http://localhost:8000';

    if (!str_ends_with(strval($_ENV['APP_URL']), '/')) {
      $_ENV['APP_URL'] .= '/';
    }

    self::$client ??= new Client([
      'base_uri' => $_ENV['APP_URL'],
    ]);
  }
}
