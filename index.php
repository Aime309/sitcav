<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Auth;
use Leaf\Auth\User;
use Leaf\Helpers\Password;

/////////////////////////
// CONSTANTES GLOBALES //
/////////////////////////

/** Por ejemplo sería: `C:\xampp\htdocs\sitcav` */
const ROOT_DIR = __DIR__;

require_once ROOT_DIR . '/vendor/autoload.php';

/** Por ejemplo sería: `http://localhost/sitcav` **NO INCLUYE `/` al FINAL** */
define(
  'FULL_BASE_URL',
  Flight::request()->getScheme()
    . '://'
    . Flight::request()->host
    . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']),
);

////////////////////////////////////////////////////////
// CARGAR VARIABLES DE ENTORNO - ver archivo .env.php //
////////////////////////////////////////////////////////
$_ENV += file_exists(ROOT_DIR . '/.env.php')
  ? require ROOT_DIR . '/.env.php'
  : [];

$_ENV += require ROOT_DIR . '/.env.dist.php';

$_ENV['GOOGLE_AUTH_REDIRECT_URI'] = FULL_BASE_URL . '/oauth2/google';
$_ENV['APP_URL'] = FULL_BASE_URL;

////////////////////////////
// CONSTANTES PARA VISTAS //
////////////////////////////
define(
  'RESOURCES_ID',
  $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '',
);

define('BASE_HREF', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
Container::getInstance()->singleton(Auth::class);
$auth = Container::getInstance()->get(Auth::class);
$auth->config('id.key', 'id');
$auth->config('db.table', 'usuarios');
// $auth->config('roles.key', 'rol');
$auth->config('timestamps', false);
$auth->config('timestamps.format', 'YYYY-MM-DD HH:mm:ss');

$auth->config(
  'password.encode',
  static fn(string $password): string => Password::hash(
    $password,
    Password::BCRYPT,
    ['cost' => 10],
  )
);

$auth->config('password.verify', Password::verify(...));
$auth->config('password.key', 'contrasena');
$auth->config('unique', ['cedula']);
$auth->config('hidden', []);
$auth->config('session', true);
$auth->config('session.lifetime', 0);

$auth->config('session.cookie', [
  'secure' => false,
  'httponly' => true,
  'samesite' => 'Strict',
]);

$auth->config('token.lifetime', null);
$auth->config('token.secret', $_ENV['TOKEN_SECRET']);

$auth->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
$auth->config('messages.loginPasswordError', $auth->config('messages.loginParamsError'));

////////////////////////////////////////////
// CONFIGURAR CONEXIÓN A LA BASE DE DATOS //
////////////////////////////////////////////
$auth->autoConnect();
$pdo = $auth->db()->connection();

if ($pdo instanceof PDO) {
  $auth->dbConnection($pdo);
}

///////////////////////
// CONFIGURAR FLIGHT //
///////////////////////
Flight::set(
  'flight.base_url',
  str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])
);

Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', false);
Flight::set('flight.log_errors', false);
Flight::set('flight.views.path', ROOT_DIR . '/resources/views');
Flight::set('flight.views.extension', '.php');
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->preserveVars = false;

///////////////////////////////////////////
// CONFIGURAR CONTENEDOR DE DEPENDENCIAS //
///////////////////////////////////////////
$container = Container::getInstance();

$container->singleton($pdo);
$container->singleton(User::class, static fn(): User => $auth->user());

Flight::registerContainerHandler($container);

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////

///////////////////////////////////
// CONFIGURAR CONTROL DE ERRORES //
///////////////////////////////////
error_reporting(
  $_ENV['ENVIRONMENT'] === 'development'
    ? E_ALL
    : E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT
);

ini_set('display_errors', $_ENV['ENVIRONMENT'] === 'development');
ini_set('display_startup_errors', $_ENV['ENVIRONMENT'] === 'development');
ini_set('html_errors', false);
ini_set('log_errors', true);
ini_set('ignore_repeated_source', true);
ini_set('error_log', ROOT_DIR . '/storage/logs/php_errors.log');

/////////////////////////////////////
// CONFIGURAR GENERACIÓN DE FECHAS //
/////////////////////////////////////
date_default_timezone_set($_ENV['TIMEZONE']);

//////////////////
// CARGAR RUTAS //
//////////////////
foreach (glob(ROOT_DIR . '/routes/*.php') as $routesFile) {
  require_once $routesFile;
}

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
