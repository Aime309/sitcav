<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Client;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class FeatureTestCase extends TestCase
{
  protected static Client $client;

  public function setUp(): void
  {
    parent::setUp();

    $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost:5000';

    if (!str_ends_with(strval($baseUrl), '/')) {
      $baseUrl .= '/';
    }

    self::$client = new Client([
      'base_uri' => $baseUrl,
    ]);

    $pdo = new PDO('sqlite:' . dirname(__DIR__, 2) . '/database/database.sqlite');
    $pdo->exec("DELETE FROM usuarios WHERE cedula = '28072391'");
    $pdo->exec("DELETE FROM proveedores WHERE rif = 'J-TEST-123'");
    $pdo->exec("DELETE FROM ventas WHERE id_cliente IN (SELECT id FROM clientes WHERE cedula = '99999999')");
    $pdo->exec("DELETE FROM apartados WHERE id_cliente IN (SELECT id FROM clientes WHERE cedula = '99999999')");
    $pdo->exec("DELETE FROM clientes WHERE cedula = '99999999'");

    $pdo->exec('
      UPDATE usuarios
      SET cedula = "12345678",
      contrasena = "$2y$10$JlyAfulFPBmL1Ktpy26E8ecxIa1EiUAxXSw/YofE4cZ1eassf6qSu",
      nombre = "Juan Pérez (Encargado)", apellidos = NULL, rol = "Encargado",
      activo = TRUE, direccion = NULL, foto_url = NULL, pregunta_1 = NULL,
      respuesta_1 = NULL, pregunta_2 = NULL, respuesta_2 = NULL,
      pregunta_3 = NULL, respuesta_3 = NULL
      WHERE id = 1
    ');
  }
}
