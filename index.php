<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Leaf\Helpers\Password;
use SITCAV\Modelos\UsuarioAutenticado;

/////////////////////////
// CONSTANTES GLOBALES //
/////////////////////////
/** Por ejemplo sería: `C:\xampp\htdocs\sitcav` */
const CARPETA_RAIZ = __DIR__;

require_once CARPETA_RAIZ . '/vendor/autoload.php';

/** Por ejemplo sería: `http://localhost/sitcav` **NO INCLUYE `/` al FINAL** */
define(
  'URL_BASE_COMPLETA',
  Flight::request()->getScheme() . '://' . Flight::request()->host . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])
);

////////////////////////////////////////////////////////
// CARGAR VARIABLES DE ENTORNO - ver archivo .env.php //
////////////////////////////////////////////////////////
$_ENV += file_exists(CARPETA_RAIZ . '/.env.php') ? require CARPETA_RAIZ . '/.env.php' : [];
$_ENV += require CARPETA_RAIZ . '/.env.dist.php';

$_ENV['GOOGLE_AUTH_REDIRECT_URI'] ??= URL_BASE_COMPLETA . '/oauth2/google';
$_ENV['APP_URL'] ??= URL_BASE_COMPLETA;

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('id.key', 'id');
auth()->config('db.table', 'usuarios');
auth()->config('roles.key', 'roles');
auth()->config('timestamps', false);
auth()->config('timestamps.format', 'YYYY-MM-DD HH:mm:ss');

auth()->config('password.encode', static function (string $password): string {
  return Password::hash($password, Password::BCRYPT, [
    'cost' => 10,
  ]);
});

auth()->config('password.verify', Password::verify(...));
auth()->config('password.key', 'clave_encriptada');
auth()->config('unique', ['email', 'cedula']);
auth()->config('hidden', []);
auth()->config('session', true);
auth()->config('session.lifetime', 0);

auth()->config('session.cookie', [
  'secure' => true,
  'httponly' => true,
  'samesite' => 'lax',
]);

auth()->config('token.lifetime', null);
auth()->config('token.secret', $_ENV['TOKEN_SECRET']);

auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));

auth()->createRoles([
  'Vendedor' => ['editar perfil', 'ver productos', 'registrar pago', 'realizar pago', 'generar factura'],
  'Empleado superior' => ['registrar cliente', 'registrar categoria', 'registrar producto', 'registrar cotizacion', 'registrar compra'],
  'Encargado' => ['editar datos del negocio', 'registrar proveedor', 'registrar empleado', 'respaldar la base de datos', 'restaurar la base de datos', 'despedir vendedor', 'promover vendedor'],
]);

// DESACTIVAR LA VERIFICACIÓN SSL DE GUZZLE (CLIENTE HTTP)
$guzzle = auth()->client('google')->getHttpClient();
$refleccionPropiedad = new ReflectionProperty($guzzle, 'config');
$refleccionPropiedad->setAccessible(true);
$config = $refleccionPropiedad->getValue($guzzle);
$refleccionPropiedad->setValue($guzzle, ['verify' => false] + $config);

///////////////////////
// CONFIGURAR FLIGHT //
///////////////////////
Flight::set('flight.base_url', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
Flight::set('flight.case_sensitive', false);
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', false);
Flight::set('flight.views.path', CARPETA_RAIZ . '/vistas');
Flight::set('flight.views.extension', '.php');
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->preserveVars = false;

/////////////////////////
// CONFIGURAR ELOQUENT //
/////////////////////////
$manager = new Manager(Container::getInstance());

$configuracion = [
  'driver' => $_ENV['DB_CONNECTION'],
  'host' => $_ENV['DB_HOST'] ?? 'localhost',
  'database' => $_ENV['DB_DATABASE'],
  'username' => $_ENV['DB_USERNAME'] ?? 'root',
  'password' => $_ENV['DB_PASSWORD'] ?? null,
];

$manager->addConnection($configuracion);
$manager->setAsGlobal();
$manager->bootEloquent();

///////////////////////////////////////////
// CONFIGURAR CONTENEDOR DE DEPENDENCIAS //
///////////////////////////////////////////
$contenedor = Container::getInstance();

$contenedor->singleton(
  PDO::class,
  static fn(): PDO => $manager->connection()->getPdo(),
);

$contenedor->singleton(
  UsuarioAutenticado::class,
  static fn(): UsuarioAutenticado => UsuarioAutenticado::query()->findOrFail(auth()->id()),
);

Flight::registerContainerHandler($contenedor);

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////
db()->connection($contenedor->get(PDO::class));
(new ReflectionProperty(auth(), 'db'))->setValue(auth(), db());

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
ini_set('log_errors', true);
ini_set('ignore_repeated_source', true);
ini_set('error_log', CARPETA_RAIZ . '/logs/php_errors.log');

Flight::map('error', static function (Throwable $error): never {
  if (str_contains($error->getPrevious()?->getMessage() ?: $error->getMessage(), UsuarioAutenticado::class)) {
    Flight::redirect('/salir');

    exit;
  }

  http_response_code(500);
  flash()->set(['Ha ocurrido un error inesperado. Por favor intente nuevamente más tarde. ❌'], 'errores');
  error_log($error->getMessage());
  Flight::redirect('/salir');

  exit;
});

Flight::map('notFound', static function (): void {
  http_response_code(404);
  Flight::render('paginas/404');
});

/////////////////////////////////////
// CONFIGURAR GENERACIÓN DE FECHAS //
/////////////////////////////////////
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'America/Caracas');

//////////////////
// CARGAR RUTAS //
//////////////////
require_once CARPETA_RAIZ . '/src/rutas/api.php';
require_once CARPETA_RAIZ . '/src/rutas/web.php';

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
