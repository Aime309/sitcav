<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

final class DashboardStatsTest extends FeatureTestCase
{
  public function setUp(): void
  {
    $_ENV['APP_URL'] = 'http://localhost:8000';
    parent::setUp();
  }

  #[Test]
  public function dashboardStatsWorks(): void
  {
    $response = self::$client->get('./api/dashboard/stats');
    $contents = json_decode($response->getBody()->getContents());

    self::assertSame(6, $contents->total_productos);
    self::assertSame(1, $contents->stock_bajo);
    self::assertSame(3, $contents->total_clientes);
    self::assertSame(1, $contents->total_ventas);
    self::assertSame(1, $contents->ventas_mes);
    self::assertSame(3, $contents->total_empleados);
    self::assertSame(2, $contents->total_proveedores);
    self::assertSame(0, $contents->total_compras);
    self::assertSame(0, $contents->total_apartados_activos);
    self::assertSame(0, $contents->total_reembolsos);
    self::assertSame(0, $contents->total_inventario);
    self::assertSame(35.5, $contents->total_cotizacion);
  }
}
