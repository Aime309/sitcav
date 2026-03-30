<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Auth;

Flight::group('/api', static function (): void {
  Flight::route('GET /status', static fn() => Flight::json(['status' => 'ok']));

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
