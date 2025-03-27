<?php

use SITCAV\Autorizadores\SoloAutenticados;
use SITCAV\Autorizadores\SoloVisitantes;
use SITCAV\Controladores\Web\ControladorDeSesion;

function renderizarSvelte(): void
{
  Flight::render('estructuras/base');
}

////////////////////
// RUTAS PRIVADAS //
////////////////////
Flight::group(
  '/@rutaPrivada:(panel|clientes|perfil|productos)/',
  static fn() => Flight::route('*', 'renderizarSvelte'),
  [SoloAutenticados::class]
);

////////////////////
// RUTAS PÚBLICAS //
////////////////////
Flight::route('POST /ingresar', [ControladorDeSesion::class, 'procesarIngreso']);
Flight::route('/salir', [ControladorDeSesion::class, 'cerrarSesion']);
Flight::route('*', 'renderizarSvelte')->addMiddleware(SoloVisitantes::class);
