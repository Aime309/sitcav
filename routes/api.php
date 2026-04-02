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
        $category = new Category;
        $category->nombre = $data->nombre;
        $category->id_usuario = $data->id_usuario ?? 1;
        $category->save();

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
      Flight::route('PUT /', static function (int $id): void {
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
      });

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
