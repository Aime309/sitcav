<?php

declare(strict_types=1);

namespace SITCAV\Tests\E2E;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase
{
  protected readonly Client $cliente;
  protected readonly string $url;

  function __construct(string $name) // @phpstan-ignore-line
  {
    parent::__construct($name);
    $carpetaRaiz = __DIR__ . '/../../';

    $this->cliente = new Client;
    $_ENV += file_exists("$carpetaRaiz/.env.php") ? require "$carpetaRaiz/.env.php" : [];
    $_ENV += require "$carpetaRaiz/.env.dist.php";

    $this->url = $_ENV['TEST_URL'];
  }
}
