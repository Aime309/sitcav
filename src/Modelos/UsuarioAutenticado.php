<?php

namespace SITCAV\Modelos;

use Illuminate\Support\Collection;

/**
 * @property-read Cotizacion $ultimaCotizacion
 * @property-read Cotizacion|null $cotizacionDeHoy
 * @property-read Collection<int, Producto> $productos
 * @property-read Collection<int, Proveedor> $proveedores
 */
final class UsuarioAutenticado extends Usuario
{
  function getUltimaCotizacionAttribute(): Cotizacion
  {
    if ($this->esEncargado) {
      return $this->cotizaciones()->orderByDesc('fecha_hora_creacion')->first() ?? new Cotizacion;
    }

    return $this->encargado->cotizaciones()->orderByDesc('fecha_hora_creacion')->first() ?? new Cotizacion;
  }

  function getCotizacionDeHoyAttribute(): ?Cotizacion
  {
    if ($this->esEncargado) {
      return $this->cotizaciones()
        ->where('fecha_hora_creacion', 'like', date('Y-m-d') . '%')
        ->orderByDesc('fecha_hora_creacion')
        ->first();
    }

    return $this->encargado->cotizaciones()
      ->where('fecha_hora_creacion', 'like', date('Y-m-d') . '%')
      ->orderByDesc('fecha_hora_creacion')
      ->first();
  }

  /** @return Collection<int, Producto> */
  function getProductosAttribute(): Collection
  {
    $mapeador = fn(Marca $marca): Collection => $marca->productos;

    if ($this->esEncargado) {
      return $this->marcas()->with('productos')->get()->map($mapeador)->flatten();
    }

    return $this->encargado->marcas()->with('productos')->get()->map($mapeador)->flatten();
  }

  /** @return Collection<int, Proveedor> */
  function getProveedoresAttribute(): Collection
  {
    $mapeador = fn(Estado $estado): Collection => $estado->proveedores;

    if ($this->esEncargado) {
      return $this->estados()->with('proveedores')->get()->map($mapeador)->flatten();
    }

    return $this->encargado->estados()->with('proveedores')->get()->map($mapeador)->flatten();
  }
}
