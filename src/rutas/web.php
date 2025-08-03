<?php

use SITCAV\Autorizadores\SoloAutenticados;
use SITCAV\Autorizadores\SoloVisitantes;
use SITCAV\Controladores\Web\ControladorDeSesion;
use SITCAV\Modelos\Evento;
use SITCAV\Modelos\Usuario;

// function renderizarSvelte(): void
// {
//   Flight::render('estructuras/base');
// }

Flight::route('GET /ingresar', function (): void {
  echo <<<html
  <h1>Ingresar</h1>
  <form method="post">
    <input type="number" name="cedula" required placeholder="Cédula" />
    <input type="password" name="clave" required placeholder="Contraseña" />
    <button>Ingresar</button>
  </form>
  html;
});

Flight::route('POST /ingresar', function (): void {
  $credenciales = Flight::request()->data;

  if (auth()->login([
    'cedula' => $credenciales->cedula,
    'clave_encriptada' => $credenciales->clave,
  ])) {
    exit('BIENVENIDO');
  } else {
    dd(auth()->errors());
  }
});

Flight::route('/salir', function (): void {
  auth()->logout();
  Flight::redirect('/ingresar');
});

Flight::route('GET /registrarse', function (): void {

});

Flight::route('POST /registrarse', function (): void {

});

Flight::route('GET /recuperar-clave/1', function (): void {
  echo <<<html

  html;
});

Flight::route('POST /recuperar-clave/1', function (): void {

});

Flight::route('GET /recuperar-clave/2', function (): void {

});

Flight::route('POST /recuperar-clave/2', function (): void {

});

Flight::route('GET /recuperar-clave/3', function (): void {

});

Flight::route('POST /recuperar-clave/3', function (): void {

});

Flight::route('GET /', function (): void {

});

Flight::route('GET /perfil', function (): void {

});

Flight::route('GET /perfil/editar', function (): void {

});

Flight::route('POST /perfil/editar', function (): void {

});

Flight::route('GET /empleados', function (): void {

});

Flight::route('/empleados/despedir', function (): void {

});

Flight::route('/empleados/promover', function (): void {

});

Flight::route('GET /eventos', function (): void {
});

////////////////////
// RUTAS PRIVADAS //
////////////////////
// Flight::group(
//   '/@rutaPrivada:(panel|clientes|perfil|productos)/',
//   static fn() => Flight::route('*', 'renderizarSvelte'),
//   [SoloAutenticados::class]
// );

////////////////////
// RUTAS PÚBLICAS //
////////////////////
// Flight::route('POST /ingresar', [ControladorDeSesion::class, 'procesarIngreso']);
// Flight::route('/salir', [ControladorDeSesion::class, 'cerrarSesion']);
// Flight::route('*', 'renderizarSvelte')->addMiddleware(SoloVisitantes::class);
