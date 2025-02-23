<?php

declare(strict_types=1);

namespace SITCAV\Tests\E2E;

use DOMDocument;
use PHPUnit\Framework\Attributes\Test;

final class LandingTest extends TestBase
{
  #[Test]
  function renderiza_la_landing(): void
  {
    $respuesta = $this->cliente->get("{$this->url}/");
    $html = new DOMDocument;
    $html->loadHTML($respuesta->getBody()->getContents());
    $titulo = $html->getElementsByTagName('title')->item(0)->textContent;

    self::assertSame(200, $respuesta->getStatusCode());
    self::assertSame($_ENV['APP_NAME'], $titulo);
  }
}
