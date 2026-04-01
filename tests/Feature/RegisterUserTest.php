<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Attributes\Test;

final class RegisterUserTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function registerWorks(): void
  {
    $response = self::$client->post('./api/usuarios', [
      'json' => [
        'cedula' => '28072391',
        'nombre' => 'Franyer',
      ],
    ]);

    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(201, $response->getStatusCode());
    self::assertTrue($contents->activo);
    self::assertNull($contents->apellidos);
    self::assertSame('28072391', $contents->cedula);
    self::assertNull($contents->direccion);
    self::assertNull($contents->foto_url);
    self::assertSame('Franyer', $contents->nombre);
    self::assertNull($contents->pregunta_1);
    self::assertNull($contents->pregunta_2);
    self::assertNull($contents->pregunta_3);
    self::assertSame('Vendedor', $contents->rol);
  }

  #[Test]
  public function cannotRegisterUserWithDuplicatedIdCard(): void
  {
    try {
      self::$client->post('./api/usuarios', [
        'json' => [
          'cedula' => 12345678,
        ],
      ]);
    } catch (ClientException $exception) {
      $contents = json_decode($exception->getResponse()->getBody()->getContents());

      self::assertSame(400, $exception->getResponse()->getStatusCode());
      self::assertFalse($contents->success);
      self::assertTrue(str_starts_with($contents->message, 'Error al crear usuario: '));
    }
  }
}
