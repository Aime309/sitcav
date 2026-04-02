<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Attributes\Test;

final class UpdateUserTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function itReplays404WithInvalidId(): void
  {
    try {
      self::$client->put('./api/usuarios/4');
    } catch (ClientException $exception) {
      self::assertSame(404, $exception->getResponse()->getStatusCode());
    }
  }

  #[Test]
  public function updateUserWorks(): void
  {
    $response = self::$client->put('./api/usuarios/1', [
      'json' => [
        'nombre' => 'Updated Name',
        'cedula' => '1234567890',
        'rol' => 'Vendedor',
        'activo' => true,
        'apellidos' => 'Updated Last Name',
        'direccion' => 'Updated Address',
        'foto_url' => 'http://example.com/updated_photo.jpg',
        'contrasena' => 'newpassword',
        'pregunta_1' => 'Updated Question 1',
        'respuesta_1' => 'Updated Answer 1',
        'pregunta_2' => 'Updated Question 2',
        'respuesta_2' => 'Updated Answer 2',
        'pregunta_3' => 'Updated Question 3',
        'respuesta_3' => 'Updated Answer 3',
      ],
    ]);

    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(1, $contents->id);
    self::assertSame('Updated Name', $contents->nombre);
    self::assertSame('1234567890', $contents->cedula);
    self::assertSame('Vendedor', $contents->rol);
    self::assertSame(true, $contents->activo);
    self::assertSame('Updated Last Name', $contents->apellidos);
    self::assertSame('Updated Address', $contents->direccion);
    self::assertSame('http://example.com/updated_photo.jpg', $contents->foto_url);
    self::assertSame('Updated Question 1', $contents->pregunta_1);
    self::assertSame('Updated Question 2', $contents->pregunta_2);
    self::assertSame('Updated Question 3', $contents->pregunta_3);

    // test password
    self::$client = new Client([
      'base_uri' => 'http://localhost:8000/',
    ]);

    $response = self::$client->post('./login', [
      'json' => [
        'usuario' => '1234567890',
        'contrasena' => 'newpassword',
      ],
    ]);

    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(true, $contents->success);
    self::assertSame('Autenticación exitosa', $contents->message);
    self::assertSame('Vendedor', $contents->rol);
    self::assertSame(1, $contents->usuario_id);
    self::assertSame('Updated Name', $contents->nombre);
    self::assertSame('1234567890', $contents->cedula);
    self::assertSame('http://example.com/updated_photo.jpg', $contents->foto_url);
  }

  #[Test]
  public function itReplays400WithInvalidData(): void
  {
    try {
      self::$client->put('./api/usuarios/1', [
        'json' => [
          'cedula' => '87654321',
        ],
      ]);
    } catch (ClientException $exception) {
      $contents = json_decode($exception->getResponse()->getBody()->getContents());

      self::assertSame(400, $exception->getResponse()->getStatusCode());

      self::assertTrue(str_starts_with(
        $contents->message,
        'Error al actualizar usuario:',
      ));
    }
  }
}
