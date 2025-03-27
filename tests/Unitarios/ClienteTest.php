<?php

declare(strict_types=1);

namespace SITCAV\Tests\Unitarios;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SITCAV\Modelos\Cliente;

final class ClienteTest extends TestCase
{
  #[Test]
  static function permite_nombres_validos(): void
  {
    $cliente = new Cliente;

    $cliente->nombres = 'simón josé antonio de la santísima  trinidad';
    $cliente->apellidos = 'bolívar ponte y palacios blanco';

    self::assertSame(
      'Simón José Antonio de la Santísima Trinidad Bolívar Ponte y Palacios Blanco',
      $cliente->nombreCompleto
    );

    $cliente = new Cliente;

    $cliente->nombres = 'eliannys de Las nieves';
    $cliente->apellidos = 'farías rendón';

    self::assertSame(
      'Eliannys de las Nieves Farías Rendón',
      $cliente->nombreCompleto
    );
  }
}
