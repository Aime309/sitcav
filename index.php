<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Enums\SessionKey;
use flight\Container;
use Leaf\Auth;
use Leaf\Auth\User;
use Leaf\Flash;
use Leaf\Form;
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
$auth = Container::getInstance()->singleton(Auth::class)->get(Auth::class);
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

$auth->createRoles([
  Role::ADMIN->name => [],
  Role::ATTENDANT->name => [],
  Role::CLIENT->name => [],
  Role::SELLER->name => [],
]);

///////////////////////////////////////////////////
// CONFIGURAR LEAF FORM (módulo de validaciones) //
///////////////////////////////////////////////////
$form = Container::getInstance()->singleton(Form::class)->get(Form::class);
$form->rule('password', '/^.{8,}$/', 'La contraseña debe tener al menos 8 caracteres.');
$form->message('min', 'El campo {Field} debe tener al menos %s caracteres.');

// DESACTIVAR LA VERIFICACIÓN SSL DE GUZZLE (CLIENTE HTTP)
$guzzle = $auth->client('google')->getHttpClient();
$guzzleConfigProperty = new ReflectionProperty($guzzle, 'config');
$guzzleConfigProperty->setAccessible(true);
$guzzleConfigPropertyValue = $guzzleConfigProperty->getValue($guzzle);

$guzzleConfigProperty->setValue(
  $guzzle,
  ['verify' => false] + $guzzleConfigPropertyValue,
);

///////////////////////
// CONFIGURAR FLIGHT //
///////////////////////
Flight::set(
  'flight.base_url',
  str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']),
);

Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', false);
Flight::set('flight.log_errors', false);
Flight::set('flight.views.path', ROOT_DIR . '/resources/views');
Flight::set('flight.views.extension', '.php');
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->preserveVars = false;

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////
$auth->autoConnect();
$pdo = $auth->db()->connection();

if ($pdo instanceof PDO) {
  $auth->dbConnection($pdo);
}

///////////////////////////////////////////
// CONFIGURAR CONTENEDOR DE DEPENDENCIAS //
///////////////////////////////////////////
$container = Container::getInstance();

$container->singleton($pdo);
$container->singleton(User::class, static fn(): User => $auth->user());

Flight::registerContainerHandler($container);

///////////////////////////////////
// CONFIGURAR CONTROL DE ERRORES //
///////////////////////////////////
error_reporting(
  $_ENV['ENVIRONMENT'] === 'development'
    ? E_ALL
    : E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT,
);

ini_set('display_errors', $_ENV['ENVIRONMENT'] === 'development');
ini_set('display_startup_errors', $_ENV['ENVIRONMENT'] === 'development');
ini_set('html_errors', true);
ini_set('log_errors', true);
ini_set('ignore_repeated_source', true);
ini_set('error_log', ROOT_DIR . '/storage/logs/php_errors.log');

Flight::map('error', static function (Throwable $error): never {
  if (str_contains($error->getPrevious()?->getMessage() ?: $error->getMessage(), User::class)) {
    Flight::redirect('/salir');

    exit;
  }

  if (str_contains($error->getMessage(), 'Template file not found')) {
    Flight::notFound();
    error_log($error->__toString());

    exit;
  }

  http_response_code(500);

  Flash::set(
    ['Ha ocurrido un error inesperado. Por favor intente nuevamente más tarde.'],
    SessionKey::ERROR_MESSAGES->name,
  );

  error_log($error->__toString());
  Flight::redirect('/');

  exit;
});

Flight::map('notFound', static function (): void {
  http_response_code(404);

  Flight::render('pages/404', key: 'slot');

  Flight::render('layouts/layout', [
    'title' => 'Página no encontrada',
  ]);
});

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
