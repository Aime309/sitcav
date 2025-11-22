<?php

namespace SITCAV\Autorizadores;

use Flight;
use Illuminate\Container\Container;
use SITCAV\Enums\ClaveSesion;
use SITCAV\Enums\Permiso;
use SITCAV\Modelos\UsuarioAutenticado;

final readonly class SoloTasaActualizada
{
  static function before()
  {
    $cotizacionDeHoy = Container::getInstance()->get(UsuarioAutenticado::class)->cotizacionDeHoy;

    if (!$cotizacionDeHoy) {
      if (auth()->user()->can(Permiso::REGISTRAR_COTIZACION->name)) {
        $ultimaCotizacion = Container::getInstance()->get(UsuarioAutenticado::class)->ultimaCotizacion;

        Flight::render('paginas/registrar-tasa-bcv', compact('ultimaCotizacion'), 'pagina');
        Flight::render('diseños/materialm-para-autenticados', ['titulo' => 'Inicio']);

        exit;
      } else {
        flash()->set(
          ['La tasa bcv no ha sido actualizada. Contacte a su encargado o empleado superior'],
          ClaveSesion::MENSAJES_ERRORES->name,
        );

        Flight::redirect('/salir');

        exit;
      }
    }
  }
}
