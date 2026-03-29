<?php

declare(strict_types=1);

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
Flight::set('flight.views.path', ROOT_DIR . '/resources/views');
Flight::set('flight.views.extension', '.php');
Flight::set('flight.content_length', true);
Flight::set('flight.v2.output_buffering', false);
Flight::view()->preserveVars = false;

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
