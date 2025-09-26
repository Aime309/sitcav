<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use SITCAV\Modelos\UsuarioAutenticado;
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
$rutaArchivoEnv = [
  'local' => CARPETA_RAIZ . '/.env',
  'distribuido' => CARPETA_RAIZ . '/.env.dist'
];

if (!file_exists($rutaArchivoEnv['local'])) {
  copy($rutaArchivoEnv['distribuido'], $rutaArchivoEnv['local']);
}

(new Dotenv)->load($rutaArchivoEnv['local']);

////////////////////////////////////////////////////
// CONFIGURAR LEAF AUTH (módulo de autenticación) //
////////////////////////////////////////////////////
auth()->config('session', true);
auth()->config('db.table', 'usuarios');
auth()->config('password.key', 'clave_encriptada');
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));
auth()->config('timestamps', false);

//////////////////////////////////////////////
// CONFIGURAR MOTOR DE PLANTILLAS DE FLIGHT //
//////////////////////////////////////////////
Flight::set('flight.views.path', CARPETA_RAIZ . '/src');

/////////////////////////
// CONFIGURAR ELOQUENT //
/////////////////////////
$manager = new Manager(Container::getInstance());

$configuracion = [
  'driver' => $_ENV['DB_CONNECTION'],
  'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
  'database' => $_ENV['DB_DATABASE'],
  'username' => $_ENV['DB_USERNAME'] ?? 'root',
  'password' => $_ENV['DB_PASSWORD'] ?? ''
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
