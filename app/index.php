<?php

require_once __DIR__ . '/configuraciones.php';

//////////////////
// CARGAR RUTAS //
//////////////////
require CARPETA_RAIZ . '/src/rutas/index.php';

//////////////////////////
// INICIAR EL FRAMEWORK //
//////////////////////////
Flight::start();
