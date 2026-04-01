<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Attributes\Test;

final class RegisterTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function cannotRegisterUserWithDuplicatedIdCard(): void
  {
    try {
      self::$client->post('./register', [
        'json' => [
          'cedula' => 12345678,
        ],
      ]);
    } catch (ClientException $exception) {
      $contents = json_decode($exception->getResponse()->getBody()->getContents());

      self::assertSame(400, $exception->getResponse()->getStatusCode());
      self::assertFalse($contents->success);
      self::assertSame('La cédula ya está registrada', $contents->message);
    }
  }

  #[Test]
  public function validRegistersWorks(): void
  {
    $response = self::$client->post('./register', [
      'json' => [
        'cedula' => 28072391,
        'nombre' => 'Franyer',
        'contrasena' => '28072391',
        'pregunta_1' => 'pregunta_1',
        'pregunta_2' => 'pregunta_2',
        'pregunta_3' => 'pregunta_3',
        'respuesta_1' => 'respuesta_1',
        'respuesta_2' => 'respuesta_2',
        'respuesta_3' => 'respuesta_3',
      ],
    ]);

    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(201, $response->getStatusCode());
    self::assertTrue($contents->success);
    self::assertSame('Usuario registrado exitosamente', $contents->message);
    self::assertTrue($contents->usuario->activo);
    self::assertNull($contents->usuario->apellidos);
    self::assertSame('28072391', $contents->usuario->cedula);
    self::assertNull($contents->usuario->direccion);
    self::assertNull($contents->usuario->foto_url);
    self::assertSame('Franyer', $contents->usuario->nombre);
    self::assertSame('pregunta_1', $contents->usuario->pregunta_1);
    self::assertSame('pregunta_2', $contents->usuario->pregunta_2);
    self::assertSame('pregunta_3', $contents->usuario->pregunta_3);
    self::assertSame('Vendedor', $contents->usuario->rol);
  }
}
