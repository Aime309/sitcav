<?php

namespace SITCAV\Autorizadores;

use Flight;
use Illuminate\Container\Container;
use SITCAV\Modelos\UsuarioAutenticado;

final readonly class SoloTasaActualizada
{
  static function before()
  {
    $cotizacionDeHoy = Container::getInstance()->get(UsuarioAutenticado::class)->cotizacionDeHoy;

    if (!$cotizacionDeHoy) {
      if (auth()->user()->can('registrar cotizacion')) {
        $ultimaCotizacion = Container::getInstance()->get(UsuarioAutenticado::class)->ultimaCotizacion;

        Flight::render('paginas/registrar-tasa-bcv', compact('ultimaCotizacion'), 'pagina');
        Flight::render('diseños/diseño-con-alpine-para-autenticados', ['titulo' => 'Inicio']);

        exit;
      } else {
        flash()->set(['LA TASA BCV NO HA SIDO ACTUALIZADA. CONTACTE A SU ENCARGADO. ❌'], 'errores');
        Flight::redirect('/salir');

        exit;
      }
    }
  }
}
