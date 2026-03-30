<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class LoginTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function validLoginWorks(): void {
    $response = self::$client->post('./login', [
      'json' => ['usuario' => 12345678, 'contrasena' => 'test1'],
    ]);

    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(true, $contents->success);
    self::assertSame('Autenticación exitosa', $contents->message);
    self::assertSame('Encargado', $contents->rol);
    self::assertSame(1, $contents->usuario_id);
    self::assertSame('Juan Pérez (Encargado)', $contents->nombre);
    self::assertSame('12345678', $contents->cedula);
    self::assertNull($contents->foto_url);
  }

  #[Test]
  #[DataProvider('invalidCredentials')]
  public function invalidLoginWorks(int $usuario, string $contrasena): void {
    try {
      self::$client->post('./login', [
        'json' => ['usuario' => $usuario, 'contrasena' => $contrasena],
      ]);
    } catch (ClientException $exception) {
      $contents = json_decode($exception->getResponse()->getBody()->getContents());

      self::assertSame(401, $exception->getResponse()->getStatusCode());
      self::assertSame(false, $contents->success);
      self::assertSame('Credenciales inválidas', $contents->message);
    }
  }

  public static function invalidCredentials(): array {
    return [
      ['usuario' => 123456788, 'contrasena' => 'test1'],
      ['usuario' => 12345678, 'contrasena' => 'test11'],
    ];
  }
}
