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
