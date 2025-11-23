<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Leaf\Helpers\Password;
use SITCAV\Enums\ClaveSesion;
use SITCAV\Enums\Permiso;
use SITCAV\Enums\Rol;
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
  Flight::request()->getScheme()
    . '://'
    . Flight::request()->host
    . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']),
);

////////////////////////////////////////////////////////
// CARGAR VARIABLES DE ENTORNO - ver archivo .env.php //
////////////////////////////////////////////////////////
$_ENV += file_exists(CARPETA_RAIZ . '/.env.php')
  ? require CARPETA_RAIZ . '/.env.php'
  : [];

$_ENV += require CARPETA_RAIZ . '/.env.dist.php';

$_ENV['GOOGLE_AUTH_REDIRECT_URI'] = URL_BASE_COMPLETA . '/oauth2/google';
$_ENV['APP_URL'] = URL_BASE_COMPLETA;

////////////////////////////
// CONSTANTES PARA VISTAS //
////////////////////////////
define(
  'ID_DE_RECURSOS',
  $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '',
);

define('BASE_HREF', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('id.key', 'id');
auth()->config('db.table', 'usuarios');
auth()->config('roles.key', 'roles');
auth()->config('timestamps', false);
auth()->config('timestamps.format', 'YYYY-MM-DD HH:mm:ss');

auth()->config(
  'password.encode',
  static fn(string $password): string => Password::hash(
    $password,
    Password::BCRYPT,
    ['cost' => 10],
  )
);

auth()->config('password.verify', Password::verify(...));
auth()->config('password.key', 'clave_encriptada');
auth()->config('unique', ['email', 'cedula']);
auth()->config('hidden', []);
auth()->config('session', true);
auth()->config('session.lifetime', 0);

auth()->config('session.cookie', [
  'secure' => false,
  'httponly' => true,
  'samesite' => 'Strict',
]);

auth()->config('token.lifetime', null);
auth()->config('token.secret', $_ENV['TOKEN_SECRET']);

auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));

auth()->createRoles([
  Rol::VENDEDOR->value => [
    Permiso::EDITAR_PERFIL->name,
    Permiso::VER_PRODUCTOS->name,
    Permiso::REGISTRAR_PAGO->name,
    Permiso::REALIZAR_VENTA->name,
    Permiso::GENERAR_FACTURA->name,
  ],
  Rol::EMPLEADO_SUPERIOR->value => [
    Permiso::REGISTRAR_CLIENTE->name,
    Permiso::REGISTRAR_CATEGORIA->name,
    Permiso::REGISTRAR_PRODUCTO->name,
    Permiso::REGISTRAR_COTIZACION->name,
    Permiso::REGISTRAR_COMPRA->name,
  ],
  Rol::ENCARGADO->value => [
    Permiso::EDITAR_DATOS_DEL_NEGOCIO->name,
    Permiso::REGISTRAR_PROVEEDOR->name,
    Permiso::REGISTRAR_EMPLEADO->name,
    Permiso::RESPALDAR_BASE_DE_DATOS->name,
    Permiso::RESTAURAR_BASE_DE_DATOS->name,
    Permiso::DESPEDIR_EMPLEADO->name,
    Permiso::RECONTRATAR_EMPLEADO->name,
    Permiso::PROMOVER_VENDEDOR->name,
    Permiso::DEGRADAR_EMPLEADO_SUPERIOR->name,
    Permiso::VER_EMPLEADOS->name,
    Permiso::VER_VENTAS->name,
    Permiso::VER_NEGOCIOS->name,
    Permiso::VER_PROVEEDORES->name,
    Permiso::VER_COMPRAS->name,
    Permiso::VER_CLIENTES->name,
    Permiso::VER_PAGOS->name,
    Permiso::RESTABLECER_CLAVE_EMPLEADO->name,
    Permiso::CONTRATAR_EMPLEADO->name,
  ],
]);

// DESACTIVAR LA VERIFICACIÓN SSL DE GUZZLE (CLIENTE HTTP)
$guzzle = auth()->client('google')->getHttpClient();
$refleccionPropiedad = new ReflectionProperty($guzzle, 'config');
$refleccionPropiedad->setAccessible(true);
$configuracionDeGuzzle = $refleccionPropiedad->getValue($guzzle);

$refleccionPropiedad->setValue(
  $guzzle,
  ['verify' => false] + $configuracionDeGuzzle
);

///////////////////////
// CONFIGURAR FLIGHT //
///////////////////////
Flight::set(
  'flight.base_url',
  str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])
);

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
$dependencias = Container::getInstance();

$dependencias->singleton(
  PDO::class,
  static fn(): PDO => $manager->connection()->getPdo(),
);

$dependencias->singleton(
  UsuarioAutenticado::class,
  static fn(): UsuarioAutenticado => UsuarioAutenticado::query()->findOrFail(auth()->id()),
);

Flight::registerContainerHandler($dependencias);

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////
db()->connection($dependencias->get(PDO::class));
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

  if (str_contains($error->getMessage(), 'Template file not found')) {
    Flight::notFound();
    error_log($error);

    exit;
  }

  http_response_code(500);

  flash()->set(
    ['Ha ocurrido un error inesperado. Por favor intente nuevamente más tarde.'],
    ClaveSesion::MENSAJES_ERRORES->name,
  );

  error_log($error);
  Flight::redirect('/');

  exit;
});

Flight::map('notFound', static function (): void {
  http_response_code(404);

  Flight::render('paginas/404', key: 'pagina');

  Flight::render('diseños/materialm-para-errores', [
    // 'titulo' => 'Página no encontrada',
  ]);
});

/////////////////////////////////////
// CONFIGURAR GENERACIÓN DE FECHAS //
/////////////////////////////////////
date_default_timezone_set($_ENV['TIMEZONE']);

//////////////////
// CARGAR RUTAS //
//////////////////
foreach (glob(CARPETA_RAIZ . '/src/rutas/*.php') as $archivoRuta) {
  require_once $archivoRuta;
}

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
