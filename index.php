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

////////////////////////////////////////////////////////
// CARGAR VARIABLES DE ENTORNO - ver archivo .env.php //
////////////////////////////////////////////////////////
$_ENV += file_exists(CARPETA_RAIZ . '/.env.php') ? require CARPETA_RAIZ . '/.env.php' : [];
$_ENV += require CARPETA_RAIZ . '/.env.dist.php';

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('session', true);
auth()->config('db.table', 'usuarios');
auth()->config('password.key', 'clave_encriptada');
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);
auth()->config('unique', ['cedula']);

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

Flight::registerContainerHandler($contenedor->get(...));

////////////////////////////////////////////////
// CONFIGURAR CONEXIÓN COMPARTIDA (Singleton) //
////////////////////////////////////////////////
db()->connection($contenedor->get(PDO::class));
$refleccionPropiedad = new ReflectionProperty(auth(), 'db');
$refleccionPropiedad->setValue(auth(), db());

///////////////////////////////////
// CONFIGURAR CONTROL DE ERRORES //
///////////////////////////////////
Flight::map('error', static function (Throwable $error): void {
  if (str_contains($error->getPrevious()?->getMessage() ?? '', UsuarioAutenticado::class)) {
    http_response_code(401);

    exit;
  }

  throw $error;
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
