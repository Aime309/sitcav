<?php

declare(strict_types=1);

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
