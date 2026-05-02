<?php

declare(strict_types=1);

use App\Enums\FormRule;
use App\Enums\Role;
use flight\Container;
use Leaf\Auth;
use Leaf\Auth\User;
use Leaf\Db;
use Leaf\Form;
use Leaf\Helpers\Password;

/////////////////////////
// CONSTANTES GLOBALES //
/////////////////////////

/** Por ejemplo sería: `C:\xampp\htdocs\sitcav` */
const ROOT_DIR = __DIR__;

require_once ROOT_DIR . '/vendor/autoload.php';

/** Por ejemplo sería: `"/sitcav"` en XAMPP y `""` en `composer serve` */
define('BASE_URL', Flight::request()->base === '/' ? '' : Flight::request()->base);

/** Por ejemplo sería: `http://localhost/sitcav` **NO INCLUYE `/` al FINAL** */
define('FULL_BASE_URL', Flight::request()->getBaseUrl() . BASE_URL);

/** @var 'development'|'production' */
define('ENVIRONMENT', str_starts_with(Flight::request()->host, 'localhost') ? 'development' : 'production');

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
  ENVIRONMENT === 'development' ? uniqid() : '',
);

const BASE_HREF = BASE_URL . '/';

///////////////////////////////////////////
// CONFIGURAR CONTENEDOR DE DEPENDENCIAS //
///////////////////////////////////////////
$container = Container::getInstance();

Flight::registerContainerHandler($container);

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
$auth = $container->singleton(Auth::class)->get(Auth::class);
$auth->config('id.key', 'id');
$auth->config('db.table', 'users');
$auth->config('roles.key', 'roles');
$auth->config('timestamps', true);
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
$auth->config('password.key', 'password');
$auth->config('unique', ['email']);
$auth->config('hidden', ['field.id', 'field.password']);
$auth->config('session', true);
$auth->config('session.lifetime', 60 * 60 * 24); // 1 día

$auth->config('session.cookie', [
  'secure' => true,
  'httponly' => true,
  'samesite' => 'lax',
]);

$auth->config('token.lifetime', $auth->config('session.lifetime'));
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
$form = $container->singleton(Form::class)->get(Form::class);

foreach (FormRule::cases() as $rule) {
  $form->addRule($rule->getName(), $rule->getHandler(), $rule->getMessage());
}

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
Flight::set('flight.base_url', BASE_URL);
Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', true);
Flight::set('flight.views.path', ROOT_DIR . '/resources/views');
Flight::set('flight.views.extension', '.php');
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::set('flight.debug', true);
Flight::set('flight.allow_method_override', true);
Flight::view()->preserveVars = false;

//////////////////////////////
// CONFIGURAR BASE DE DATOS //
//////////////////////////////
$auth->autoConnect();
$db = $container->singleton($auth->db())->get(Db::class);
$pdo = $container->singleton($db->connection())->get(PDO::class);

//////////////////////////////
// EJECUTAR LAS MIGRACIONES //
//////////////////////////////
foreach (glob(ROOT_DIR . '/database/migrations/*.sql') as $sqlFilePath) {
  $sql = file_get_contents($sqlFilePath);
  $pdo->exec($sql);
}

///////////////////////////////////
// CONFIGURAR CONTROL DE ERRORES //
///////////////////////////////////
error_reporting(
  ENVIRONMENT === 'development'
    ? E_ALL
    : E_ALL & ~E_DEPRECATED & ~E_STRICT,
);

ini_set('display_errors', ENVIRONMENT === 'development');
ini_set('display_startup_errors', ENVIRONMENT === 'development');
ini_set('log_errors', true);
ini_set('ignore_repeated_errors', true);
ini_set('ignore_repeated_source', true);
ini_set('report_memleaks', true);
ini_set('report_zend_debug', false);
ini_set('xmlrpc_errors', false);
ini_set('xmlrpc_error_number', false);
ini_set('html_errors', true);
ini_set('docref_root', '/phpmanual/');
ini_set('docref_ext', '.html');
ini_set('error_prepend_string', '<span style="color: #ff0000">');
ini_set('error_append_string', '</span>');
ini_set('error_log', ROOT_DIR . '/storage/logs/php_errors.log');
ini_set('syslog.ident', 'php');
ini_set('syslog.facility', 'user');
ini_set('syslog.filter', 'ascii');
ini_set('windows.show_crt_warning', false);

Flight::map('error', static function (Throwable $error): never {
  if (str_contains($error->getPrevious()?->getMessage() ?: $error->getMessage(), User::class)) {
    error_log($error->__toString());
    Flight::redirect('/salir');

    exit;
  }

  if (str_contains($error->getMessage(), 'Template file not found')) {
    error_log($error->__toString());
    Flight::notFound();

    exit;
  }

  http_response_code(500);
  error_log($error->__toString());

  Flight::render('pages/500', key: 'slot');

  Flight::render('layouts/layout', [
    'title' => 'Error interno del servidor',
  ]);

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
date_default_timezone_set('America/Caracas');

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
