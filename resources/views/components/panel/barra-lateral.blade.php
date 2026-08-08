<aside class="d-sidebar">
    <div class="brand">
        <div class="brand-text">
            <div class="brand-name">{{ config('app.name') }}</div>
        </div>
    </div>
    <x-panel.seccion etiqueta="Espacio de trabajo">
        <x-panel.enlace-navegacion
            uri="{{ match (true) {
                !empty($sucursal) => route(
                    'panel.negocios.{negocio}.sucursales.{sucursal}',
                    [
                        'negocio' => $sucursal->negocio,
                        'sucursal' => $sucursal,
                    ],
                ),
                !empty($negocio) => route(
                    'panel.negocios.{negocio}',
                    ['negocio' => $negocio],
                ),
                default => '',
            } }}"
            icono='<path d="M3 12 12 3l9 9"/><path d="M5 10v10h14V10"/>'
            texto="Inicio" />

        @if ($usuario->roles->contains('rol', 'administrador'))
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.empleados',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Empleados" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.sucursales',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Sucursales" />
        @endif

        @if ($usuario->roles->contains('rol', 'encargado'))
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.proveedores',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Proveedores" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.clientes',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Clientes" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.compras',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Compras" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.ventas',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Ventas" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.reservas',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Reservas" />
            <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.inventario',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Inventario" />
        @endif
        <x-panel.enlace-navegacion
            uri="{{ route(
                'panel.negocios.{negocio}.productos',
                ['negocio' => $negocio],
            ) }}"
            icono=""
            texto="Productos" />
    </x-panel.seccion>
</aside>
