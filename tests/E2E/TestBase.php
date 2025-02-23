<?php

declare(strict_types=1);

namespace SITCAV\Tests\E2E;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

abstract class TestBase extends TestCase
{
  protected readonly Client $cliente;
  protected readonly string $url;

  function __construct(string $name)
  {
    parent::__construct($name);

    $this->cliente = new Client;
    (new Dotenv)->load(__DIR__ . '/../../.env');
    $this->url = $_ENV['TEST_URL'];
  }
}
