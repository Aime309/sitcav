<?php

namespace SITCAV\Modelos;

/**
 * @property-read Cotizacion $ultimaCotizacion
 * @property-read Cotizacion|null $cotizacionDeHoy
 */
final class UsuarioAutenticado extends Usuario
{
  function getUltimaCotizacionAttribute(): Cotizacion
  {
    if ($this->id_encargado) {
      return $this->encargado->cotizaciones()->orderByDesc('fecha_hora_creacion')->first() ?? new Cotizacion;
    }

    return $this->cotizaciones()->orderByDesc('fecha_hora_creacion')->first() ?? new Cotizacion;
  }

  function getCotizacionDeHoyAttribute(): ?Cotizacion
  {
    if ($this->id_encargado) {
      return $this->encargado->cotizaciones()
        ->where('fecha_hora_creacion', 'like', date('Y-m-d') . '%')
        ->orderByDesc('fecha_hora_creacion')
        ->first();
    }

    return $this->cotizaciones()
      ->where('fecha_hora_creacion', 'like', date('Y-m-d') . '%')
      ->orderByDesc('fecha_hora_creacion')
      ->first();
  }
}
