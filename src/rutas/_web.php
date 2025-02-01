<?php

use SITCAV\Autorizadores\GarantizaQueElUsuarioEstaAutenticado;
use SITCAV\Autorizadores\GarantizaQueElUsuarioNoEstaAutenticado;
use SITCAV\Controladores\Web\ControladorDeSesion;

function renderizarSvelte(): void
{
  Flight::render('estructuras/base');
}

Flight::route('POST /ingresar', [ControladorDeSesion::class, 'procesarIngreso']);
Flight::route('/salir', [ControladorDeSesion::class, 'cerrarSesion']);

Flight::group('/panel/', static function (): void {
  Flight::route('*', 'renderizarSvelte');
}, [GarantizaQueElUsuarioEstaAutenticado::class]);

Flight::route('*', 'renderizarSvelte')
  ->addMiddleware(GarantizaQueElUsuarioNoEstaAutenticado::class);
