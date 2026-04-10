<?php

declare(strict_types=1);

namespace Tests\Feature;

final class ProveedoresTest extends FeatureTestCase
{
  public function test_can_list_proveedores(): void
  {
    $response = self::$client->get('api/proveedores');

    $this->assertSame(200, $response->getStatusCode());
    $this->assertIsArray(json_decode($response->getBody()->getContents(), true));
  }

  public function test_can_create_proveedor(): void
  {
    $data = [
      'nombre' => 'Proveedor de Prueba',
      'rif' => 'J-TEST-123',
      'telefono' => '0212-1234567',
      'direccion' => 'Dirección de prueba',
      'id_estado' => 1,
      'id_localidad' => 1,
      'id_sector' => 1
    ];

    $response = self::$client->post('api/proveedores', [
      'json' => $data
    ]);

    $this->assertSame(201, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertSame($data['nombre'], $result['nombre']);
    $this->assertSame($data['rif'], $result['rif']);
  }

  public function test_can_update_proveedor(): void
  {
    // First create one to update
    $data = [
      'nombre' => 'Proveedor a Actualizar',
      'rif' => 'J-TEST-123',
      'telefono' => '0212-1234567'
    ];

    $response = self::$client->post('api/proveedores', [
      'json' => $data
    ]);
    $created = json_decode($response->getBody()->getContents(), true);
    $id = $created['id'];

    $updateData = [
      'nombre' => 'Proveedor Actualizado',
      'rif' => 'J-TEST-123',
      'telefono' => '0212-7654321'
    ];

    $response = self::$client->put("api/proveedores/$id", [
      'json' => $updateData
    ]);

    $this->assertSame(200, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertSame($updateData['nombre'], $result['nombre']);
    $this->assertSame($updateData['telefono'], $result['telefono']);
  }

  public function test_can_delete_proveedor(): void
  {
    // First create one to delete
    $data = [
      'nombre' => 'Proveedor a Eliminar',
      'rif' => 'J-TEST-123'
    ];

    $response = self::$client->post('api/proveedores', [
      'json' => $data
    ]);
    $created = json_decode($response->getBody()->getContents(), true);
    $id = $created['id'];

    $response = self::$client->delete("api/proveedores/$id");

    $this->assertSame(200, $response->getStatusCode());
    $result = json_decode($response->getBody()->getContents(), true);
    $this->assertTrue($result['success']);
  }
}
