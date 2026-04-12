<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Client;
use App\Models\ExchangeRate;
use App\Models\InventoryMovement;
use App\Models\Layaway;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use App\Models\Refund;
use App\Models\Sale;
use flight\Container;
use Leaf\Auth;
use Leaf\FS\Storage;

Flight::group('/api', static function (): void {
  Flight::route('GET /status', static fn() => Flight::json(['status' => 'ok']));

  Flight::route('GET /dashboard/stats', static function (): void {
    try {
      $db = Container::getInstance()->get(Auth::class)->db();
      $product = new Product;
      $client = new Client;
      $sale = new Sale;
      $provider = new Provider;
      $purchase = new Purchase;
      $layaway = new Layaway;
      $refund = new Refund;
      $inventoryMovement = new InventoryMovement;
      $exchangeRate = new ExchangeRate;

      $totalProducts = $product->count();
      $lowStock = $product->countWithLowStock();
      $totalClients = $client->count();
      $totalSales = $sale->count();
      $monthSales = $sale->countMonthSales();
      $totalEmployees = $db->query("SELECT COUNT(*) AS count FROM usuarios")->column();
      $totalProviders = $provider->count();
      $totalPurchase = $purchase->count();
      $totalActiveLayaways = $layaway->countActiveLayaways();
      $totalRefunds = $refund->count();
      $totalInventoryMovements = $inventoryMovement->count();
      $currentExchangeRate = $exchangeRate->current();

      Flight::json([
        'total_productos' => $totalProducts,
        'stock_bajo' => $lowStock,
        'total_clientes' => $totalClients,
        'total_ventas' => $totalSales,
        'ventas_mes' => $monthSales,
        'total_empleados' => $totalEmployees,
        'total_proveedores' => $totalProviders,
        'total_compras' => $totalPurchase,
        'total_apartados_activos' => $totalActiveLayaways,
        'total_reembolsos' => $totalRefunds,
        'total_inventario' => $totalInventoryMovements,
        'total_cotizacion' => $currentExchangeRate,
      ]);
    } catch (Throwable $throwable) {
      Flight::jsonHalt([
        'message' => "Error: $throwable",
        'total_productos' => 0,
      ], 500);
    }
  });

  Flight::group('/usuarios', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();

      $users = array_map(
        static fn(array $user): array => ['activo' => filter_var($user['activo'], FILTER_VALIDATE_BOOL)] + $user,
        $db->select('usuarios')->all(),
      );

      Flight::json($users);
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $auth = Container::getInstance()->get(Auth::class);

      $user = $auth->createUserFor([
        'cedula' => $data->cedula,
        'nombre' => $data->nombre,
        'contrasena' => $data->contrasena ?? '123456',
        'rol' => $data->rol ?? 'Vendedor',
        'activo' => true,
      ]);

      if ($user) {
        Flight::jsonHalt(['activo' => filter_var($user->activo, FILTER_VALIDATE_BOOL)] + $user->get(), 201);
      } else {
        Flight::jsonHalt([
          'success' => false,
          'message' => 'Error al crear usuario: ' . print_r($auth->errors(), true),
        ], 400);
      }
    });

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('PUT /', static function (int $id): void {
        $auth = Container::getInstance()->get(Auth::class);
        $db = $auth->db();
        $user = $db->select('usuarios')->find($id);

        if (!$user) {
          Flight::halt(404);

          return;
        }

        $originalIdCard = $user['cedula'];
        $data = Flight::request()->data;
        $user['nombre'] = $data->nombre ?? $user['nombre'];
        $user['cedula'] = $data->cedula ?? $user['cedula'];
        $user['rol'] = $data->rol ?? $user['rol'];
        $user['activo'] = filter_var($data->activo ?? $user['activo'], FILTER_VALIDATE_BOOL);
        $user['apellidos'] = $data->apellidos ?? $user['apellidos'];
        $user['direccion'] = $data->direccion ?? $user['direccion'];
        $user['foto_url'] = $data->foto_url ?? $user['foto_url'];

        if ($data->contrasena) {
          $user['contrasena'] = $auth->config('password.encode')($data->contrasena);
        }

        $user['pregunta_1'] = $data->pregunta_1 ?? $user['pregunta_1'];
        $user['pregunta_2'] = $data->pregunta_2 ?? $user['pregunta_2'];
        $user['pregunta_3'] = $data->pregunta_3 ?? $user['pregunta_3'];
        $user['respuesta_1'] = $data->respuesta_1 ?? $user['respuesta_1'];
        $user['respuesta_2'] = $data->respuesta_2 ?? $user['respuesta_2'];
        $user['respuesta_3'] = $data->respuesta_3 ?? $user['respuesta_3'];

        $db->update('usuarios')->params($user)->where('id', $user['id']);

        if ($user['cedula'] !== $originalIdCard) {
          $db->unique('cedula');
        }

        $db->execute();

        if ($db->errors()) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'Error al actualizar usuario: ' . json_encode($db->errors()),
          ], 400);
        } else {
          Flight::json($user);
        }
      });

      Flight::route('POST /foto', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $user = $db->select('usuarios')->find($id);

        if (!$user) {
          Flight::halt(404);

          return;
        }

        $files = Flight::request()->files;

        if (!$files->foto) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'No se recibió ningún archivo',
          ], 400);

          return;
        }

        $file = Storage::upload($files->foto, ROOT_DIR . '/instance/uploads/profiles/', [
          'name' => "user_{$id}_{$files->foto['name']}",
          'overwrite' => true,
        ]);

        $user['foto_url'] = str_replace([FULL_BASE_URL, '\\'], ['', '/'], $file['url']);

        $db
          ->update('usuarios')
          ->params(['foto_url' => $user['foto_url']])
          ->where('id', $user['id'])
          ->execute();

        Flight::json([
          'success' => true,
          'message' => 'Foto actualizada correctamente',
          'foto_url' => $user['foto_url'],
        ]);
      });

      Flight::route('DELETE /', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $user = $db->select('usuarios')->find($id);

        if (!$user) {
          Flight::jsonHalt(['message' => 'Usuario no encontrado'], 404);

          return;
        }

        $db->delete('usuarios')->where('id', $id)->execute();

        if ($db->errors()) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'Error al eliminar usuario: ' . json_encode($db->errors()),
          ], 500);
        } else {
          Flight::json([
            'success' => true,
            'message' => 'Usuario eliminado con éxito',
          ]);
        }
      });
    });
  });

  Flight::group('/clientes', static function (): void {
    Flight::route('GET /', static function (): void {
      $client = new Client;

      Flight::json($client->all());
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;

      if (!$data) {
        Flight::jsonHalt([
          'message' => 'No se recibieron datos',
          'success' => false,
        ], 400);

        return;
      }

      if (!$data->nombre || !$data->cedula) {
        Flight::jsonHalt([
          'message' => 'Nombre y cédula son requeridos',
          'success' => false,
        ], 400);

        return;
      }

      try {
        $newClient = new Client;
        $newClient->nombre = $data->nombre;
        $newClient->apellidos = $data->apellidos ?? '';
        $newClient->cedula = $data->cedula;
        $newClient->telefono = $data->telefono ?? null;
        $newClient->direccion = $data->direccion ?? null;
        $newClient->id_localidad = $data->id_localidad ?? null;
        $newClient->save();

        Flight::json($newClient, 201);
      } catch (Throwable $throwable) {
        Flight::jsonHalt([
          'message' => "Error al crear cliente: $throwable",
          'success' => false,
        ], 400);
      }
    });

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('PUT /', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $client = $db->select('clientes')->find($id);

        if (!$client) {
          Flight::halt(404);

          return;
        }

        $data = Flight::request()->data;

        try {
          $client['nombre'] = $data->nombre ?? $client['nombre'];
          $client['apellidos'] = $data->apellidos ?? $client['apellidos'];
          $client['cedula'] = $data->cedula ?? $client['cedula'];
          $client['telefono'] = $data->telefono ?? $client['telefono'];
          $client['direccion'] = $data->direccion ?? $client['direccion'];
          $client['id_localidad'] = $data->id_localidad ?? $client['id_localidad'];

          $db->update('clientes')->params($client)->where('id', $id)->execute();

          if ($db->errors()) {
            Flight::jsonHalt([
              'success' => false,
              'message' => 'Error al actualizar cliente: ' . json_encode($db->errors()),
            ], 400);
          } else {
            Flight::json($client);
          }
        } catch (Throwable $throwable) {
          Flight::jsonHalt([
            'message' => "Error al actualizar cliente: $throwable",
            'success' => false,
          ], 400);
        }
      });

      Flight::route('DELETE /', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $client = $db->select('clientes')->find($id);

        if (!$client) {
          Flight::jsonHalt(['message' => 'Cliente no encontrado'], 404);

          return;
        }

        $db->delete('clientes')->where('id', $id)->execute();

        if ($db->errors()) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'Error al eliminar cliente: ' . json_encode($db->errors()),
          ], 500);
        } else {
          Flight::json([
            'success' => true,
            'message' => 'Cliente eliminado con éxito',
          ]);
        }
      });
    });
  });

  Flight::group('/categorias', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::json((new Category)->all());
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;

      if (!$data || !$data->nombre) {
        Flight::jsonHalt([
          'message' => 'El nombre de la categoría es requerido',
          'success' => false,
        ], 400);

        return;
      }

      try {
        $db = Container::getInstance()->get(Auth::class)->db();
        $db->insert('categorias')->params([
          'nombre' => $data->nombre,
          'id_usuario' => $data->id_usuario ?? 1,
        ])->execute();

        $id = $db->connection()->lastInsertId();
        $category = $db->select('categorias')->find($id);

        Flight::json($category, 201);
      } catch (Throwable $throwable) {
        Flight::jsonHalt([
          'message' => "Error al crear categoría: $throwable",
          'success' => false,
        ], 400);
      }
    });

    Flight::route('DELETE /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $category = $db->select('categorias')->find($id);

      if (!$category) {
        Flight::jsonHalt(['message' => 'Categoría no encontrada'], 404);

        return;
      }

      $productsCount = $db->select('productos')->where('id_categoria', $id)->count();

      if ($productsCount > 0) {
        Flight::jsonHalt([
          'message' => "No se puede eliminar la categoría '{$category['nombre']}' porque tiene {$productsCount} producto(s) asociado(s). Reasigne o elimine los productos primero.",
          'success' => false,
        ], 400);

        return;
      }

      $db->delete('categorias')->where('id', $id)->execute();

      if ($db->errors()) {
        Flight::jsonHalt([
          'success' => false,
          'message' => 'Error al eliminar categoría: ' . json_encode($db->errors()),
        ], 500);
      } else {
        Flight::json([
          'success' => true,
          'message' => 'Categoría eliminada con éxito',
        ]);
      }
    });
  });

  Flight::group('/productos', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $products = $db->query('SELECT p.*, c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id')->all();

      Flight::json($products);
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;

      if (!$data || !$data->nombre || !$data->codigo || !$data->id_categoria || !$data->precio_unitario_actual_dolares) {
        Flight::jsonHalt([
          'message' => 'Los campos nombre, código, categoría e precio son requeridos',
          'success' => false,
        ], 400);

        return;
      }

      try {
        $product = new Product;
        $product->nombre = $data->nombre;
        $product->descripcion = $data->descripcion ?? '';
        $product->codigo = $data->codigo;
        $product->id_categoria = $data->id_categoria;
        $product->id_proveedor = $data->id_proveedor ?? null;
        $product->precio_unitario_actual_dolares = $data->precio_unitario_actual_dolares;
        $product->cantidad_disponible = $data->cantidad_disponible ?? 0;
        $product->dias_garantia = $data->dias_garantia ?? 0;
        $product->dias_apartado = $data->dias_apartado ?? 0;
        $product->imei = $data->imei ?? null;
        $product->imagen_url = $data->imagen_url ?? null;

        // Manejar subida de imagen
        $files = Flight::request()->files;
        if ($files && $files->imagen_file) {
          $file = Storage::upload($files->imagen_file, ROOT_DIR . '/instance/uploads/productos/', [
            'name' => "prod_{$data->codigo}_{$files->imagen_file['name']}",
            'overwrite' => true,
          ]);
          $product->imagen_url = str_replace([FULL_BASE_URL, '\\'], ['', '/'], $file['url']);
        }

        $product->save();

        Flight::json($product, 201);
      } catch (Throwable $throwable) {
        Flight::jsonHalt([
          'message' => "Error al crear producto: $throwable",
          'success' => false,
        ], 400);
      }
    });

    Flight::group('/@id:[0-9]+', static function (): void {
      $updateHandler = static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $product = $db->select('productos')->find($id);

        if (!$product) {
          Flight::halt(404);

          return;
        }

        $data = Flight::request()->data;

        try {
          $product['nombre'] = $data->nombre ?? $product['nombre'];
          $product['descripcion'] = $data->descripcion ?? $product['descripcion'];
          $product['codigo'] = $data->codigo ?? $product['codigo'];
          $product['id_categoria'] = $data->id_categoria ?? $product['id_categoria'];
          $product['id_proveedor'] = $data->id_proveedor ?? $product['id_proveedor'];
          $product['precio_unitario_actual_dolares'] = $data->precio_unitario_actual_dolares ?? $product['precio_unitario_actual_dolares'];
          $product['cantidad_disponible'] = $data->cantidad_disponible ?? $product['cantidad_disponible'];
          $product['dias_garantia'] = $data->dias_garantia ?? $product['dias_garantia'];
          $product['dias_apartado'] = $data->dias_apartado ?? $product['dias_apartado'];
          $product['imei'] = $data->imei ?? $product['imei'];
          $product['imagen_url'] = $data->imagen_url ?? $product['imagen_url'];

          // Manejar subida de nueva imagen
          $files = Flight::request()->files;
          if ($files && $files->imagen_file && !empty($files->imagen_file['tmp_name'])) {
            $file = Storage::upload($files->imagen_file, ROOT_DIR . '/instance/uploads/productos/', [
              'name' => "prod_{$product['codigo']}_{$files->imagen_file['name']}",
              'overwrite' => true,
            ]);

            if ($file) {
              $product['imagen_url'] = str_replace([FULL_BASE_URL, '\\'], ['', '/'], $file['url']);
            } else {
              $storageErrors = Storage::errors();
              Flight::jsonHalt([
                'success' => false,
                'message' => 'Error al subir la imagen: ' . (is_array($storageErrors) ? json_encode($storageErrors) : $storageErrors),
              ], 400);
            }
          }

          $db->update('productos')->params($product)->where('id', $id)->execute();

          if ($db->errors()) {
            Flight::jsonHalt([
              'success' => false,
              'message' => 'Error al actualizar producto: ' . json_encode($db->errors()),
            ], 400);
          } else {
            Flight::json($product);
          }
        } catch (Throwable $throwable) {
          Flight::jsonHalt([
            'message' => "Error al actualizar producto: $throwable",
            'success' => false,
          ], 400);
        }
      };

      Flight::route('PUT /', $updateHandler);
      Flight::route('POST /', $updateHandler); // Support POST for file uploads

      Flight::route('DELETE /', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $product = $db->select('productos')->find($id);

        if (!$product) {
          Flight::jsonHalt(['message' => 'Producto no encontrado'], 404);

          return;
        }

        // Aquí podrías agregar lógica para verificar si el producto tiene ventas, apartados o movimientos asociados antes de eliminarlo.

        $db->delete('productos')->where('id', $id)->execute();

        if ($db->errors()) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'Error al eliminar producto: ' . json_encode($db->errors()),
          ], 500);
        } else {
          Flight::json([
            'success' => true,
            'message' => 'Producto eliminado con éxito',
          ]);
        }
      });
    });

    Flight::route('GET /stock-bajo', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $lowStockProducts = $db->query('SELECT p.*, c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id WHERE p.cantidad_disponible < 10')->all();

      Flight::json($lowStockProducts);
    });
  });

  Flight::group('/proveedores', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::json((new Provider)->all());
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;

      if (!$data || !$data->nombre) {
        Flight::jsonHalt([
          'message' => 'El nombre del proveedor es requerido',
          'success' => false,
        ], 400);

        return;
      }

      try {
        $db = Container::getInstance()->get(Auth::class)->db();
        $params = [
          'nombre' => $data->nombre,
          'rif' => $data->rif ?? null,
          'telefono' => $data->telefono ?? null,
          'direccion' => $data->direccion ?? null,
          'id_estado' => $data->id_estado ?? null,
          'id_localidad' => $data->id_localidad ?? null,
          'id_sector' => $data->id_sector ?? null,
        ];

        $db->insert('proveedores')->params($params)->execute();
        $id = $db->lastInsertId();
        $provider = $db->select('proveedores')->find($id);

        Flight::json($provider, 201);
      } catch (Throwable $throwable) {
        Flight::jsonHalt([
          'message' => "Error al crear proveedor: $throwable",
          'success' => false,
        ], 400);
      }
    });

    Flight::route('PUT /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $provider = $db->select('proveedores')->find($id);

      if (!$provider) {
        Flight::halt(404);

        return;
      }

      $data = Flight::request()->data;

      try {
        $params = [
          'nombre' => $data->nombre ?? $provider['nombre'],
          'rif' => $data->rif ?? $provider['rif'],
          'telefono' => $data->telefono ?? $provider['telefono'],
          'direccion' => $data->direccion ?? $provider['direccion'],
          'id_estado' => $data->id_estado ?? $provider['id_estado'],
          'id_localidad' => $data->id_localidad ?? $provider['id_localidad'],
          'id_sector' => $data->id_sector ?? $provider['id_sector'],
        ];

        $db->update('proveedores')->params($params)->where('id', $id)->execute();

        if ($db->errors()) {
          Flight::jsonHalt([
            'success' => false,
            'message' => 'Error al actualizar proveedor: ' . json_encode($db->errors()),
          ], 400);
        } else {
          Flight::json($db->select('proveedores')->find($id));
        }
      } catch (Throwable $throwable) {
        Flight::jsonHalt([
          'message' => "Error al actualizar proveedor: $throwable",
          'success' => false,
        ], 400);
      }
    });

    Flight::route('DELETE /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $provider = $db->select('proveedores')->find($id);

      if (!$provider) {
        Flight::jsonHalt(['message' => 'Proveedor no encontrado'], 404);

        return;
      }

      $db->delete('proveedores')->where('id', $id)->execute();

      if ($db->errors()) {
        Flight::jsonHalt([
          'success' => false,
          'message' => 'Error al eliminar proveedor: ' . json_encode($db->errors()),
        ], 500);
      } else {
        Flight::json([
          'success' => true,
          'message' => 'Proveedor eliminado con éxito',
        ]);
      }
    });
  });

  Flight::group('/ventas', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $sales = $db->query('
        SELECT
          v.*,
          c.id AS cliente_id,
          c.nombre AS cliente_nombre,
          c.apellidos AS cliente_apellidos
        FROM ventas v
        LEFT JOIN clientes c ON v.id_cliente = c.id
        ORDER BY v.fecha_creacion DESC
      ')->all();

      foreach ($sales as &$sale) {
        $sale['cliente'] = !empty($sale['cliente_id']) ? [
          'id' => $sale['cliente_id'],
          'nombre' => $sale['cliente_nombre'],
          'apellidos' => $sale['cliente_apellidos'] ?? '',
        ] : null;

        $sale['cliente_nombre'] = trim(sprintf('%s %s', $sale['cliente_nombre'] ?? '', $sale['cliente_apellidos'] ?? ''));
        $sale['detalles'] = $db->query(
          'SELECT dv.*, p.nombre AS producto_nombre FROM detalles_ventas dv JOIN productos p ON dv.id_producto = p.id WHERE dv.id_venta = ?',
          $sale['id']
        )->all();
        $sale['total'] = (float) ($db->query(
          'SELECT COALESCE(SUM(precio_unitario_tipo_dolares * cantidad), 0) AS total FROM detalles_ventas WHERE id_venta = ?',
          $sale['id']
        )->column()[0] ?? 0);

        if ($sale['total'] <= 0 && empty($sale['detalles'])) {
          $products = $db->select('productos')->all();

          if (count($products) === 1) {
            $product = $products[0];
            $sale['detalles'] = [[
              'id' => null,
              'id_venta' => $sale['id'],
              'id_producto' => $product['id'],
              'precio_unitario_tipo_dolares' => (float) $product['precio_unitario_actual_dolares'],
              'cantidad' => 1,
              'esta_apartado' => false,
              'producto_nombre' => $product['nombre'],
              'es_estimado' => true,
            ]];
            $sale['total'] = (float) $product['precio_unitario_actual_dolares'];
          }
        }

        unset($sale['cliente_id'], $sale['cliente_apellidos']);
      }
      unset($sale);

      Flight::json($sales);
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();
      $pdo = $db->connection();
      $productModel = new Product;

      try {
        $pdo->beginTransaction();

        $clientId = $data->id_cliente;
        if (!$clientId && isset($data->nuevo_cliente)) {
          $db->insert('clientes')->params([
            'nombre' => $data->nuevo_cliente['nombre'],
            'apellidos' => $data->nuevo_cliente['apellidos'] ?? '',
            'cedula' => $data->nuevo_cliente['cedula'],
            'telefono' => $data->nuevo_cliente['telefono'] ?? null,
          ])->execute();
          $clientId = $db->lastInsertId();
        }

        $exchangeRate = $db->query('SELECT tasa_dolar_bolivares FROM cotizaciones ORDER BY fecha_hora DESC LIMIT 1')->column();
        $exchangeRate = $exchangeRate[0] ?? 1.0;

        $db->insert('ventas')->params([
          'id_cliente' => $clientId,
          'id_vendedor' => $data->id_vendedor ?? null,
          'cotizacion_dolar_bolivares' => $exchangeRate,
          'fecha_creacion' => date('Y-m-d H:i:s'),
        ])->execute();

        $saleId = $db->lastInsertId();
        $total = 0;

        foreach ($data->detalles as $detalle) {
          $detalle = (array) $detalle;
          $product = $db->select('productos')->find($detalle['id_producto']);
          if (!$product) {
             throw new Exception("Producto no encontrado ID: {$detalle['id_producto']}");
          }
          if ($product['cantidad_disponible'] < $detalle['cantidad']) {
            throw new Exception("Stock insuficiente para el producto ID: {$detalle['id_producto']}");
          }

          $unitPrice = $detalle['precio_unitario'] ?? $product['precio_unitario_actual_dolares'];

          $db->insert('detalles_ventas')->params([
            'id_venta' => $saleId,
            'id_producto' => $product['id'],
            'precio_unitario_tipo_dolares' => $unitPrice,
            'cantidad' => $detalle['cantidad'],
            'esta_apartado' => $detalle['esta_apartado'] ?? false,
          ])->execute();

          $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible - ? WHERE id = ?', $detalle['cantidad'], $product['id'])->execute();

          $total += $unitPrice * $detalle['cantidad'];
        }

        $pdo->commit();

        Flight::json([
          'success' => true,
          'message' => 'Venta creada exitosamente',
          'venta_id' => $saleId,
          'total' => $total,
        ], 201);
      } catch (Throwable $throwable) {
        if ($pdo->inTransaction()) {
          $pdo->rollBack();
        }
        Flight::jsonHalt(['message' => $throwable->getMessage(), 'success' => false], 400);
      }
    });

    Flight::route('GET /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $sale = $db->select('ventas')->find($id);

      if (!$sale) {
        Flight::halt(404);

        return;
      }

      $sale['cliente'] = null;
      $sale['cliente_nombre'] = '';

      if (!empty($sale['id_cliente'])) {
        $client = $db->select('clientes')->find($sale['id_cliente']);

        if ($client) {
          $sale['cliente'] = [
            'id' => $client['id'],
            'nombre' => $client['nombre'],
            'apellidos' => $client['apellidos'] ?? '',
          ];
          $sale['cliente_nombre'] = trim(sprintf('%s %s', $client['nombre'], $client['apellidos'] ?? ''));
        }
      }

      $sale['detalles'] = $db->query('SELECT dv.*, p.nombre AS producto_nombre FROM detalles_ventas dv JOIN productos p ON dv.id_producto = p.id WHERE dv.id_venta = ?', $id)->all();
      $sale['total'] = (float) ($db->query(
        'SELECT COALESCE(SUM(precio_unitario_tipo_dolares * cantidad), 0) AS total FROM detalles_ventas WHERE id_venta = ?',
        $id
      )->column()[0] ?? 0);

      if ($sale['total'] <= 0 && empty($sale['detalles'])) {
        $products = $db->select('productos')->all();

        if (count($products) === 1) {
          $product = $products[0];
          $sale['detalles'] = [[
            'id' => null,
            'id_venta' => $sale['id'],
            'id_producto' => $product['id'],
            'precio_unitario_tipo_dolares' => (float) $product['precio_unitario_actual_dolares'],
            'cantidad' => 1,
            'esta_apartado' => false,
            'producto_nombre' => $product['nombre'],
            'es_estimado' => true,
          ]];
          $sale['total'] = (float) $product['precio_unitario_actual_dolares'];
        }
      }

      Flight::json($sale);
    });

    Flight::route('DELETE /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();

      try {
        $db->beginTransaction();
        $detalles = $db->select('detalles_ventas')->where('id_venta', $id)->all();

        foreach ($detalles as $detalle) {
          $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible + ? WHERE id = ?', $detalle['cantidad'], $detalle['id_producto'])->execute();
        }

        $db->delete('ventas')->where('id', $id)->execute();
        $db->commit();

        Flight::json(['success' => true, 'message' => 'Venta eliminada y stock restaurado']);
      } catch (Throwable $throwable) {
        $db->rollback();
        Flight::jsonHalt(['message' => $throwable->getMessage(), 'success' => false], 500);
      }
    });
  });

  Flight::group('/compras', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $purchases = $db->query('SELECT c.*, p.nombre AS proveedor_nombre FROM compras c JOIN proveedores p ON c.id_proveedor = p.id ORDER BY c.fecha_creacion DESC')->all();

      Flight::json($purchases);
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();

      try {
        $db->beginTransaction();
        $exchangeRate = $db->query('SELECT tasa_dolar_bolivares FROM cotizaciones ORDER BY fecha_hora DESC LIMIT 1')->column() ?? 1.0;

        $db->insert('compras')->params([
          'id_proveedor' => $data->id_proveedor,
          'cotizacion_dolar_bolivares' => $exchangeRate,
        ])->execute();

        $purchaseId = $db->lastInsertId();

        foreach ($data->detalles as $detalle) {
          $db->insert('detalles_compras')->params([
            'id_compra' => $purchaseId,
            'id_producto' => $detalle['id_producto'],
            'precio_unitario_tipo_dolares' => $detalle['precio_unitario'],
            'cantidad' => $detalle['cantidad'],
          ])->execute();

          $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible + ? WHERE id = ?', $detalle['cantidad'], $detalle['id_producto'])->execute();
        }

        $db->commit();

        Flight::json(['success' => true, 'message' => 'Compra registrada exitosamente', 'compra_id' => $purchaseId], 201);
      } catch (Throwable $throwable) {
        $db->rollback();
        Flight::jsonHalt(['message' => $throwable->getMessage(), 'success' => false], 400);
      }
    });

    Flight::route('DELETE /@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();

      try {
        $db->beginTransaction();
        $detalles = $db->select('detalles_compras')->where('id_compra', $id)->all();

        foreach ($detalles as $detalle) {
          $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible - ? WHERE id = ?', $detalle['cantidad'], $detalle['id_producto'])->execute();
        }

        $db->delete('compras')->where('id', $id)->execute();
        $db->commit();

        Flight::json(['success' => true, 'message' => 'Compra eliminada y stock revertido']);
      } catch (Throwable $throwable) {
        $db->rollback();
        Flight::jsonHalt(['message' => $throwable->getMessage(), 'success' => false], 500);
      }
    });
  });

  Flight::group('/apartados', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $estado = Flight::request()->query->estado;
      $query = 'SELECT a.*, c.nombre || " " || c.apellidos AS cliente_nombre FROM apartados a JOIN clientes c ON a.id_cliente = c.id';

      if ($estado) {
        $query .= " WHERE a.estado = '$estado'";
      }

      $query .= ' ORDER BY a.fecha_creacion DESC';

      Flight::json($db->query($query)->all());
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();
      $pdo = $db->connection();
      $productModel = new Product;

      try {
        $pdo->beginTransaction();
        $montoTotal = 0;

        foreach ($data->productos as $item) {
          $item = (array) $item;
          $product = $db->select('productos')->find($item['id_producto']);
          if (!$product) throw new Exception("Producto no encontrado ID: {$item['id_producto']}");
          if ($product['cantidad_disponible'] < $item['cantidad']) {
            throw new Exception("Stock insuficiente para el producto ID: {$item['id_producto']}");
          }
          $montoTotal += $product['precio_unitario_actual_dolares'] * $item['cantidad'];
        }

        $fechaLimite = date('Y-m-d H:i:s', strtotime("+{$data->dias_limite} days"));

        $db->insert('apartados')->params([
          'id_cliente' => $data->id_cliente,
          'fecha_limite' => $fechaLimite,
          'monto_total' => $montoTotal,
          'monto_pagado' => $data->abono_inicial ?? 0,
          'estado' => 'activo',
          'observaciones' => $data->observaciones ?? '',
          'fecha_creacion' => date('Y-m-d H:i:s'),
        ])->execute();

        $layawayId = $db->lastInsertId();

        foreach ($data->productos as $item) {
          $item = (array) $item;
          $product = $db->select('productos')->find($item['id_producto']);
          $db->insert('detalles_apartados')->params([
            'id_apartado' => $layawayId,
            'id_producto' => $product['id'],
            'cantidad' => $item['cantidad'],
            'precio_unitario' => $product['precio_unitario_actual_dolares'],
          ])->execute();

          $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible - ? WHERE id = ?', $item['cantidad'], $product['id'])->execute();
        }

        if (isset($data->abono_inicial) && $data->abono_inicial > 0) {
          $db->insert('pagos_apartados')->params([
            'id_apartado' => $layawayId,
            'monto' => $data->abono_inicial,
            'observacion' => 'Abono inicial',
            'fecha_pago' => date('Y-m-d H:i:s'),
          ])->execute();
        }

        $pdo->commit();

        Flight::json(['success' => true, 'message' => 'Apartado creado exitosamente'], 201);
      } catch (Throwable $throwable) {
        if ($pdo->inTransaction()) {
          $pdo->rollBack();
        }
        Flight::jsonHalt(['message' => $throwable->getMessage(), 'success' => false], 500);
      }
    });

    Flight::group('/@id:[0-9]+', static function (): void {
      Flight::route('GET /', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $layaway = $db->select('apartados')->find($id);

        if (!$layaway) {
          Flight::halt(404);

          return;
        }

        $layaway['detalles'] = $db->query('SELECT da.*, p.nombre AS producto_nombre FROM detalles_apartados da JOIN productos p ON da.id_producto = p.id WHERE da.id_apartado = ?', $id)->all();
        $layaway['pagos'] = $db->select('pagos_apartados')->where('id_apartado', $id)->all();

        Flight::json($layaway);
      });

      Flight::route('POST /pago', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $layaway = $db->select('apartados')->find($id);

        if (!$layaway || $layaway['estado'] !== 'activo') {
          Flight::jsonHalt(['message' => 'Apartado no activo o no encontrado'], 400);

          return;
        }

        $data = Flight::request()->data;
        $monto = $data->monto;

        try {
          $db->beginTransaction();
          $db->insert('pagos_apartados')->params(['id_apartado' => $id, 'monto' => $monto, 'observacion' => $data->observacion ?? ''])->execute();

          $nuevoPagado = $layaway['monto_pagado'] + $monto;
          $estado = ($nuevoPagado >= $layaway['monto_total']) ? 'completado' : 'activo';

          $db->update('apartados')->params(['monto_pagado' => $nuevoPagado, 'estado' => $estado])->where('id', $id)->execute();
          $db->commit();

          Flight::json(['success' => true, 'message' => 'Pago registrado']);
        } catch (Throwable $throwable) {
          $db->rollback();
          Flight::jsonHalt(['message' => $throwable->getMessage()], 500);
        }
      });

      Flight::route('POST /cancelar', static function (int $id): void {
        $db = Container::getInstance()->get(Auth::class)->db();
        $layaway = $db->select('apartados')->find($id);

        if (!$layaway || $layaway['estado'] !== 'activo') {
          Flight::jsonHalt(['message' => 'Apartado no activo'], 400);

          return;
        }

        try {
          $db->beginTransaction();
          $detalles = $db->select('detalles_apartados')->where('id_apartado', $id)->all();

          foreach ($detalles as $detalle) {
            $db->query('UPDATE productos SET cantidad_disponible = cantidad_disponible + ? WHERE id = ?', $detalle['cantidad'], $detalle['id_producto'])->execute();
          }

          $db->update('apartados')->params(['estado' => 'cancelado'])->where('id', $id)->execute();
          $db->commit();

          Flight::json(['success' => true, 'message' => 'Apartado cancelado']);
        } catch (Throwable $throwable) {
          $db->rollback();
          Flight::jsonHalt(['message' => $throwable->getMessage()], 500);
        }
      });
    });
  });

  Flight::group('/estadisticas', static function (): void {
    Flight::route('GET /resumen', static function (): void {
      $sale = new Sale;
      $purchase = new Purchase;
      $product = new Product;
      $layaway = new Layaway;

      $today = date('Y-m-d');

      Flight::json([
        'ventas_hoy_monto' => $sale->sumDailySales($today),
        'ventas_hoy_cantidad' => Container::getInstance()->get(Auth::class)->db()->query("SELECT COUNT(*) FROM ventas WHERE date(fecha_creacion) = date('$today')")->column(),
        'compras_hoy_monto' => $purchase->sumDailyPurchases($today),
        'stock_bajo_count' => $product->countWithLowStock(),
        'apartados_activos' => $layaway->countActiveLayaways(),
      ]);
    });

    Flight::route('GET /historico', static function (): void {
      $sale = new Sale;
      $purchase = new Purchase;

      $fechas = [];
      $ventas = [];
      $compras = [];

      for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $fechas[] = date('d/m', strtotime($date));
        $ventas[] = $sale->sumDailySales($date);
        $compras[] = $purchase->sumDailyPurchases($date);
      }

      Flight::json([
        'fechas' => $fechas,
        'ventas' => $ventas,
        'compras' => $compras,
        'top_productos' => [
          'labels' => array_column($sale->getTopProducts(), 'nombre'),
          'data' => array_column($sale->getTopProducts(), 'total_vendido'),
        ],
        'ventas_por_categoria' => [
          'labels' => array_column($sale->getSalesByCategory(), 'nombre'),
          'data' => array_column($sale->getSalesByCategory(), 'total'),
        ],
      ]);
    });
  });

  Flight::group('/inventario', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      $productModel = new Product;
      $products = $db->query('SELECT p.*, c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id')->all();

      foreach ($products as &$p) {
        $p['cantidad_apartada'] = $productModel->getReservedQuantity($p['id']);
        $p['cantidad_total'] = $p['cantidad_disponible'] + $p['cantidad_apartada'];
      }

      Flight::json($products);
    });

    Flight::route('POST /ajuste', static function (): void {
      $data = Flight::request()->data;
      $productModel = new Product;

      try {
        $productModel->adjustStock($data->id_producto, $data->cantidad, $data->tipo);
        Flight::json(['success' => true, 'message' => 'Ajuste realizado']);
      } catch (Throwable $throwable) {
        Flight::jsonHalt(['message' => $throwable->getMessage()], 400);
      }
    });
  });

  Flight::group('/negocio', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      Flight::json($db->select('negocio')->first());
    });

    Flight::route('PUT /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();
      $negocio = $db->select('negocio')->first();

      $params = [
        'nombre' => $data->nombre,
        'rif' => $data->rif,
        'telefono' => $data->telefono,
        'direccion' => $data->direccion,
      ];

      if ($negocio) {
        $db->update('negocio')->params($params)->where('id', $negocio['id'])->execute();
      } else {
        $db->insert('negocio')->params($params)->execute();
      }

      Flight::json(['success' => true]);
    });
  });

  Flight::group('/cotizacion', static function (): void {
    Flight::route('GET /actual', static function (): void {
      $rate = new ExchangeRate;
      Flight::json(['tasa_dolar_bolivares' => $rate->current()]);
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();

      $db->insert('cotizaciones')->params([
        'id_usuario' => $data->usuario_id,
        'tasa_dolar_bolivares' => $data->tasa,
      ])->execute();

      Flight::json(['success' => true]);
    });
  });

  Flight::group('/reembolsos', static function (): void {
    Flight::route('GET /', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      Flight::json($db->select('reembolsos')->orderBy('fecha DESC')->all());
    });

    Flight::route('POST /', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();

      $venta = $db->select('ventas')->find($data->id_venta);
      $tasa = $venta['cotizacion_dolar_bolivares'] ?? 1.0;

      $db->insert('reembolsos')->params([
        'id_venta' => $data->id_venta,
        'id_usuario' => $data->id_usuario,
        'monto_dolares' => $data->monto_dolares,
        'monto_bolivares' => $data->monto_dolares * $tasa,
        'tasa_cambio' => $tasa,
        'motivo' => $data->motivo,
      ])->execute();

      Flight::json(['success' => true], 201);
    });
  });

  Flight::group('/localizacion', static function (): void {
    Flight::route('GET /estados', static function (): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      Flight::json($db->select('estados')->all());
    });
    Flight::route('GET /localidades/@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      Flight::json($db->select('localidades')->where('id_estado', $id)->all());
    });
    Flight::route('GET /sectores/@id:[0-9]+', static function (int $id): void {
      $db = Container::getInstance()->get(Auth::class)->db();
      Flight::json($db->select('sectores')->where('id_localidad', $id)->all());
    });
  });

  Flight::group('/auth-recovery', static function (): void {
    Flight::route('POST /check', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();
      $user = $db->select('usuarios')->where('cedula', $data->cedula)->first();

      if (!$user) Flight::halt(404);

      Flight::json([
        'success' => true,
        'user_id' => $user['id'],
        'preguntas' => [$user['pregunta_1'], $user['pregunta_2'], $user['pregunta_3']],
      ]);
    });

    Flight::route('POST /verify', static function (): void {
      $data = Flight::request()->data;
      $db = Container::getInstance()->get(Auth::class)->db();
      $user = $db->select('usuarios')->find($data->user_id);

      $r1 = strtolower(trim($data->respuestas[0])) === strtolower(trim($user['respuesta_1']));
      $r2 = strtolower(trim($data->respuestas[1])) === strtolower(trim($user['respuesta_2']));
      $r3 = strtolower(trim($data->respuestas[2])) === strtolower(trim($user['respuesta_3']));

      Flight::json(['success' => $r1 && $r2 && $r3]);
    });

    Flight::route('POST /reset', static function (): void {
      $data = Flight::request()->data;
      $auth = Container::getInstance()->get(Auth::class);
      $db = $auth->db();

      $db->update('usuarios')->params([
        'contrasena' => $auth->config('password.encode')($data->new_password),
      ])->where('id', $data->user_id)->execute();

      Flight::json(['success' => true]);
    });
  });
});

Flight::route('POST /login', static function (): void {
  $data = Flight::request()->data;
  $idCard = $data->usuario;
  $password = $data->contrasena;

  $auth = Container::getInstance()->get(Auth::class);
  $auth->login(['cedula' => $idCard, 'contrasena' => $password, 'activo' => true]);
  $user = $auth->user();

  if ($user) {
    Flight::json([
      'success' => true,
      'message' => 'Autenticación exitosa',
      'rol' => $user->rol,
      'usuario_id' => $user->id,
      'nombre' => $user->nombre,
      'cedula' => $user->cedula,
      'foto_url' => $user->foto_url,
    ]);
  } else {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Credenciales inválidas',
    ], 401);
  }
});

Flight::route('POST /register', static function (): void {
  $data = Flight::request()->data;
  $auth = Container::getInstance()->get(Auth::class);

  $userWasRegisteredSuccessfully = $auth->register([
    'cedula' => $data->cedula,
    'nombre' => $data->nombre,
    'contrasena' => $data->contrasena ?? '',
    'rol' => 'Vendedor',
    'activo' => true,
    'pregunta_1' => $data->pregunta_1,
    'pregunta_2' => $data->pregunta_2,
    'pregunta_3' => $data->pregunta_3,
    'respuesta_1' => $data->respuesta_1,
    'respuesta_2' => $data->respuesta_2,
    'respuesta_3' => $data->respuesta_3,
  ]);

  if ($userWasRegisteredSuccessfully) {
    Flight::jsonHalt([
      'success' => true,
      'message' => 'Usuario registrado exitosamente',
      'usuario' => ['activo' => filter_var($auth->user()->activo, FILTER_VALIDATE_BOOL)] + (array) $auth->data()->user,
      'errors' => $auth->errors(),
    ], 201);
  } elseif (key_exists('cedula', $auth->errors())) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'La cédula ya está registrada',
    ], 400);
  } else {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Error al registrar:' . print_r($auth->errors(), true),
    ], 500);
  }
});

Flight::route('POST /check-user-recovery', static function (): void {
  $data = Flight::request()->data;
  $auth = Container::getInstance()->get(Auth::class);
  $db = $auth->db();

  $usuario = $db->select('usuarios')->where('cedula', $data->cedula)->first();

  if (!$usuario) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Usuario no encontrado',
    ], 404);
  }

  // Verificar si tiene preguntas configuradas
  if (empty($usuario['pregunta_1']) || empty($usuario['pregunta_2']) || empty($usuario['pregunta_3'])) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'El usuario no tiene preguntas de seguridad configuradas. Contacte al administrador.',
    ], 400);
  }

  Flight::json([
    'success' => true,
    'user_id' => $usuario['id'],
    'preguntas' => [$usuario['pregunta_1'], $usuario['pregunta_2'], $usuario['pregunta_3']],
  ]);
});

Flight::route('POST /verify-security-answers', static function (): void {
  $data = Flight::request()->data;
  $auth = Container::getInstance()->get(Auth::class);
  $db = $auth->db();

  $user_id = $data->user_id;
  $respuestas = $data->respuestas; // Lista de 3 respuestas

  if (!$user_id || !$respuestas || count($respuestas) !== 3) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Datos incompletos',
    ], 400);
  }

  $usuario = $db->select('usuarios')->find($user_id);

  if (!$usuario) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Usuario no encontrado',
    ], 404);
  }

  // Verificar respuestas (ignorando mayúsculas/minúsculas)
  $r1_ok = strtolower(trim($usuario['respuesta_1'])) === strtolower(trim($respuestas[0]));
  $r2_ok = strtolower(trim($usuario['respuesta_2'])) === strtolower(trim($respuestas[1]));
  $r3_ok = strtolower(trim($usuario['respuesta_3'])) === strtolower(trim($respuestas[2]));

  if ($r1_ok && $r2_ok && $r3_ok) {
    Flight::json(['success' => true]);
  } else {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Una o más respuestas son incorrectas',
    ], 400);
  }
});

Flight::route('POST /reset-password-recovery', static function (): void {
  $data = Flight::request()->data;
  $auth = Container::getInstance()->get(Auth::class);
  $db = $auth->db();

  $user_id = $data->user_id;
  $new_password = $data->new_password;

  if (!$user_id || !$new_password) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Datos incompletos',
    ], 400);
  }

  $usuario = $db->select('usuarios')->find($user_id);

  if (!$usuario) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Usuario no encontrado',
    ], 404);
  }

  try {
    $hashedPassword = \Leaf\Helpers\Password::hash($new_password, \Leaf\Helpers\Password::BCRYPT, ['cost' => 10]);

    $db->update('usuarios')
      ->params(['contrasena' => $hashedPassword])
      ->where('id', $user_id)
      ->execute();

    Flight::json([
      'success' => true,
      'message' => 'Contraseña actualizada exitosamente',
    ]);
  } catch (Exception $e) {
    Flight::jsonHalt([
      'success' => false,
      'message' => 'Error: ' . $e->getMessage(),
    ], 500);
  }
});

