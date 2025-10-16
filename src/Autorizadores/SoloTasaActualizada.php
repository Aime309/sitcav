<?php

namespace SITCAV\Autorizadores;

use Flight;
use SITCAV\Modelos\Cotizacion;

final readonly class SoloTasaActualizada
{
  function before()
  {
    $cotizacionDeHoy = Cotizacion::query()->where('fecha_hora_creacion', 'LIKE', date('Y-m-d') . '%')->first();

    if (
      !$cotizacionDeHoy
      && in_array(auth()->user()?->rol, ['Encargado', 'Empleado superior'])
    ) {
      Flight::render('paginas/registrar-tasa-bcv', [], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-autenticados', ['titulo' => 'Inicio']);

      exit;
    } elseif (!$cotizacionDeHoy && auth()->user()?->rol === 'Vendedor') {
      flash()->set(['LA TASA BCV NO HA SIDO ACTUALIZADA. CONTACTE A SU ENCARGADO. ❌'], 'errores');
      Flight::redirect('/salir');

      exit;
    }
  }
}
