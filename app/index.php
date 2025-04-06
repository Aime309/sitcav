<?php

require_once __DIR__ . '/configuraciones.php';

//////////////////
// CARGAR RUTAS //
//////////////////
require_once CARPETA_RAIZ . '/src/rutas/api.php';
require_once CARPETA_RAIZ . '/src/rutas/web.php';

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
