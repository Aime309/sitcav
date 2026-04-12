<?php

declare(strict_types=1);

namespace Tests\Feature;

final class FullMigrationTest extends FeatureTestCase
{
  public function setUp(): void
  {
    parent::setUp();
    self::$client = new \GuzzleHttp\Client([
      'base_uri' => 'http://localhost:8000/',
    ]);
  }

  public function test_can_list_ventas(): void
  {
    $response = self::$client->get('api/ventas');
    $this->assertSame(200, $response->getStatusCode());
    $this->assertIsArray(json_decode($response->getBody()->getContents(), true));
  }

  public function test_can_create_venta_with_new_cliente(): void
  {
    $data = [
      'nuevo_cliente' => [
        'nombre' => 'Cliente Test',
        'apellidos' => 'Venta',
        'cedula' => '99999999'
      ],
      'detalles' => [
        [
          'id_producto' => 1,
          'cantidad' => 1
        ]
      ]
    ];

    $response = self::$client->post('api/ventas', [
      'json' => $data
    ]);

    $this->assertSame(201, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertTrue($result['success']);
    $this->assertArrayHasKey('venta_id', $result);
  }

  public function test_ventas_include_cliente_and_detalles(): void
  {
    $response = self::$client->post('api/ventas', [
      'json' => [
        'id_vendedor' => 1,
        'nuevo_cliente' => [
          'nombre' => 'Cliente Test',
          'apellidos' => 'Venta',
          'cedula' => '99999999'
        ],
        'detalles' => [
          [
            'id_producto' => 1,
            'cantidad' => 1,
            'precio_unitario' => 1
          ]
        ]
      ]
    ]);

    $result = json_decode($response->getBody()->getContents(), true);
    $ventaId = $result['venta_id'];

    $salesResponse = self::$client->get('api/ventas');
    $sales = json_decode($salesResponse->getBody()->getContents(), true);
    $sale = null;

    foreach ($sales as $item) {
      if ((int) $item['id'] === (int) $ventaId) {
        $sale = $item;
        break;
      }
    }

    $this->assertNotNull($sale);
    $this->assertSame('Cliente Test', $sale['cliente']['nombre']);
    $this->assertSame('Venta', $sale['cliente']['apellidos']);
    $this->assertSame('Cliente Test Venta', $sale['cliente_nombre']);
    $this->assertEquals(1.0, $sale['total']);
    $this->assertNotEmpty($sale['detalles']);

    $saleResponse = self::$client->get("api/ventas/$ventaId");
    $saleDetail = json_decode($saleResponse->getBody()->getContents(), true);

    $this->assertSame('Cliente Test', $saleDetail['cliente']['nombre']);
    $this->assertSame('Venta', $saleDetail['cliente']['apellidos']);
    $this->assertSame('Cliente Test Venta', $saleDetail['cliente_nombre']);
    $this->assertEquals(1.0, $saleDetail['total']);
    $this->assertNotEmpty($saleDetail['detalles']);
  }

  public function test_can_get_estadisticas_resumen(): void
  {
    $response = self::$client->get('api/estadisticas/resumen');
    $this->assertSame(200, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertArrayHasKey('ventas_hoy_monto', $result);
    $this->assertArrayHasKey('stock_bajo_count', $result);
  }

  public function test_can_generate_factura_pdf(): void
  {
    $response = self::$client->get('api/factura/1');
    $this->assertSame(200, $response->getStatusCode());
    $this->assertSame('application/pdf', $response->getHeaderLine('Content-Type'));
    $this->assertStringContainsString('inline; filename="factura_1.pdf"', $response->getHeaderLine('Content-Disposition'));
  }

  public function test_can_generate_consultas_pdf(): void
  {
    $response = self::$client->get('api/consultas/ventas/pdf');
    $this->assertSame(200, $response->getStatusCode());
    $this->assertSame('application/pdf', $response->getHeaderLine('Content-Type'));
    $this->assertStringContainsString('inline; filename="reporte_consultas.pdf"', $response->getHeaderLine('Content-Disposition'));
  }

  public function test_can_create_apartado(): void
  {
    // Create client first
    self::$client->post('api/clientes', [
      'json' => ['nombre' => 'Cliente', 'apellidos' => 'Apartado', 'cedula' => '99999999']
    ]);
    
    $response = self::$client->get('api/clientes');
    $clientes = json_decode($response->getBody()->getContents(), true);
    $clientId = 0;
    foreach($clientes as $c) if($c['cedula'] === '99999999') $clientId = $c['id'];

    $data = [
      'id_cliente' => $clientId,
      'dias_limite' => 30,
      'productos' => [
        ['id_producto' => 2, 'cantidad' => 1]
      ],
      'abono_inicial' => 10
    ];

    $response = self::$client->post('api/apartados', [
      'json' => $data
    ]);

    $this->assertSame(201, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertTrue($result['success']);
  }

  public function test_can_get_inventario_with_reserved_stock(): void
  {
    $response = self::$client->get('api/inventario');
    $this->assertSame(200, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertNotEmpty($result);
    $this->assertArrayHasKey('cantidad_apartada', $result[0]);
  }
}
