<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Dotenv\Dotenv;

/** Absolute path to project root */
const ROOT = __DIR__ . '/..';

require ROOT . '/vendor/autoload.php';

(new Dotenv)->load(ROOT . '/.env');
auth()->config('session', true);
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);

Flight::set('flight.views.path', ROOT . '/src/views');

$container = new Container;
$container->singleton(PDO::class, static fn(): PDO => db()->connection());
$manager = new Manager;

$manager->addConnection([
  'driver' => $_ENV['DB_CONNECTION'],
  'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
  'database' => $_ENV['DB_DATABASE'],
  'username' => $_ENV['DB_USERNAME'] ?? 'root',
  'password' => $_ENV['DB_PASSWORD'] ?? ''
]);

$manager->setAsGlobal();
$manager->bootEloquent();

require ROOT . '/src/routes/index.php';
Flight::start();
