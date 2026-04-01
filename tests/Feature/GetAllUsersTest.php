<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

final class GetAllUsersTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function getAllUsersWorks(): void
  {
    $response = self::$client->get('./api/usuarios');
    $contents = json_decode($response->getBody()->getContents());

    self::assertCount(4, $contents);
    self::assertTrue($contents[0]->activo);
    self::assertNull($contents[0]->apellidos);
    self::assertSame('12345678', $contents[0]->cedula);
    self::assertNull($contents[0]->direccion);
    self::assertNull($contents[0]->foto_url);
    self::assertSame(1, $contents[0]->id);
    self::assertSame('Juan Pérez (Encargado)', $contents[0]->nombre);
    self::assertNull($contents[0]->pregunta_1);
    self::assertNull($contents[0]->pregunta_2);
    self::assertNull($contents[0]->pregunta_3);
    self::assertSame('Encargado', $contents[0]->rol);
  }
}
