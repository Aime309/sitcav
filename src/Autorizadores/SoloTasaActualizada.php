<?php

namespace SITCAV\Autorizadores;

use SITCAV\Modelos\Cotizacion;
use SITCAV\Modelos\UsuarioAutenticado;

final readonly class SoloTasaActualizada
{
  function __construct(private UsuarioAutenticado $usuarioAutenticado)
  {
    // ...
  }

  function before()
  {
    $cotizacionDeHoy = Cotizacion::query()->where('fecha_hora_creacion', 'LIKE', date('Y-m-d') . '%')->first();
    $ultimaCotizacion = Cotizacion::query()->latest()->get()[0];
    $tasaDePagina = json_decode(file_get_contents('https://ve.dolarapi.com/v1/dolares'))[0]->promedio;
    $modalBcvId = uniqid();

    if (
      !$cotizacionDeHoy
      && in_array($this->usuarioAutenticado->rol, ['Encargado', 'Empleado superior'])
    ) {
      exit(<<<html
      <dialog id="$modalBcvId">
        <form action="./cotizaciones" method="post">
          Tasa según <a href="https://www.bcv.org.ve/">bcv.org.ve</a> <output>$tasaDePagina</output>
          <label>
            Tasa BCV
            <input
              type="number"
              step=".01"
              name="nueva_tasa"
              required
              placeholder="Tasa BCV"
              value="$ultimaCotizacion->tasa_bcv" />
          </label>
          <button>Actualizar</button>
        </form>
      </dialog>

      <script>document.getElementById('$modalBcvId').showModal()</script>
      html);
    } elseif (!$cotizacionDeHoy && $this->usuarioAutenticado->rol === 'Vendedor') {
      exit('LA TASA BCV NO HA SIDO ACTUALIZADA. CONTACTE A SU ENCARGADO. ❌');
    }
  }
}
