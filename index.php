<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
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
$_ENV += file_exists(CARPETA_RAIZ . '/.env.php')
  ? require CARPETA_RAIZ . '/.env.php'
  : require CARPETA_RAIZ . '/.env.dist.php';

$_ENV['GOOGLE_AUTH_REDIRECT_URI'] ??= URL_BASE_COMPLETA . '/oauth2/google';
$_ENV['APP_URL'] ??= URL_BASE_COMPLETA;

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('session', true);
auth()->config('db.table', 'usuarios');
auth()->config('password.key', 'clave_encriptada');
auth()->config('token.secret', $_ENV['TOKEN_SECRET']);
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);
auth()->config('unique', ['cedula']);
auth()->config('roles.key', 'roles');

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

//////////////////////////////////////////////
// CONFIGURAR MOTOR DE PLANTILLAS DE FLIGHT //
//////////////////////////////////////////////
Flight::set('flight.views.path', CARPETA_RAIZ . '/vistas');

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

Flight::set('flight.base_url', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
Flight::registerContainerHandler($contenedor->get(...));

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////
db()->connection($contenedor->get(PDO::class));

///////////////////////////////////
// CONFIGURAR CONTROL DE ERRORES //
///////////////////////////////////
Flight::map('error', static function (Throwable $error): void {
  if (str_contains($error->getPrevious()?->getMessage() ?? '', UsuarioAutenticado::class)) {
    flash()->set(['Ha ocurrido un error, por favor ingrese nuevamente'], 'errores');
    Flight::redirect('/salir');

    exit;
  }

  var_dump($error);
  exit;
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
