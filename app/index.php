<?php

require_once __DIR__ . '/configuraciones.php';

//////////////////
// CARGAR RUTAS //
//////////////////
require CARPETA_RAIZ . '/src/rutas/web.php';
require CARPETA_RAIZ . '/src/rutas/api.php';

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
