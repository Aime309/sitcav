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

  function __construct(string $name) // @phpstan-ignore-line
  {
    parent::__construct($name);

    $this->cliente = new Client;
    $rutaVariablesEntorno = __DIR__ . '/../../.env';
    $dotenv = new Dotenv;

    if (file_exists($rutaVariablesEntorno)) {
      $dotenv->load($rutaVariablesEntorno);
    } else {
      $dotenv->load("$rutaVariablesEntorno.dist");
    }

    $this->url = $_ENV['TEST_URL'];
  }
}
