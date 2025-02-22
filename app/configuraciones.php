<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use SITCAV\Modelos\Cliente;
use Symfony\Component\Dotenv\Dotenv;

/////////////////////////
// CONSTANTES GLOBALES //
/////////////////////////
/** Por ejemplo sería: `C:\xampp\htdocs\sitcav` */
const CARPETA_RAIZ = __DIR__ . '/..';

require CARPETA_RAIZ . '/vendor/autoload.php';

////////////////////////////////////////////////////
// CARGAR VARIABLES DE ENTORNO - ver archivo .env //
////////////////////////////////////////////////////
if (!file_exists(__DIR__ . '/../.env')) {
  copy(__DIR__ . '/../.env.dist', __DIR__ . '/../.env');
}

(new Dotenv)->load(CARPETA_RAIZ . '/.env');

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('session', true);
auth()->config('db.table', 'usuarios');
auth()->config('password.key', 'clave');
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);

//////////////////////////////////////////////
// CONFIGURAR MOTOR DE PLANTILLAS DE FLIGHT //
//////////////////////////////////////////////
Flight::set('flight.views.path', CARPETA_RAIZ . '/src');

///////////////////////////////////////////
// CONFIGURAR CONTENEDOR DE DEPENDENCIAS //
///////////////////////////////////////////
$container = new Container;
$container->singleton(PDO::class, static fn(): PDO => db()->connection());

/////////////////////////
// CONFIGURAR ELOQUENT //
/////////////////////////
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
