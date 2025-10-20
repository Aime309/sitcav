<?php

namespace SITCAV\Autorizadores;

use Flight;
use SITCAV\Modelos\Cotizacion;

final readonly class SoloTasaActualizada
{
  static function before()
  {
    $cotizacionDeHoy = Cotizacion::hoy();

    if (!$cotizacionDeHoy) {
      if (auth()->user()->can('registrar cotizacion')) {
        Flight::render('paginas/registrar-tasa-bcv', [], 'pagina');
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
