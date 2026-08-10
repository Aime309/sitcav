@props(['sucursal', 'negocio', 'usuario'])

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
            {{-- <x-panel.enlace-navegacion
                uri="{{ route(
                    'panel.negocios.{negocio}.sucursales',
                    ['negocio' => $negocio],
                ) }}"
                icono=""
                texto="Sucursales" /> --}}
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
    <div class="sidebar-footer">
        <{{ $usuario->roles->contains('rol', 'administrador')
            ? 'a'
            : 'div'
        }} class="workspace" href="{{ $usuario->roles->contains('rol', 'administrador') ? route('panel.negocios') : 'javascript:' }}">
            <div class="workspace-avatar"></div>
            <div class="workspace-text">
                <div class="workspace-name">
                    @if (!empty($sucursal))
                        {{ $sucursal->nombre }}
                    @else
                        {{ $negocio->nombre }}
                    @endif
                </div>
                @if (!empty($sucursal))
                    <div class="workspace-role">
                        {{ $sucursal->negocio->nombre }}
                    </div>
                @endif
            </div>
            @if ($usuario->roles->contains('rol', 'administrador'))
                <svg
                    class="workspace-chev"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path d="m7 9 5-5 5 5" />
                    <path d="m7 15 5 5 5-5" />
                </svg>
            @endif
        </{{ $usuario->roles->contains('rol', 'administrador')
            ? 'a'
            : 'div'
        }}>
    </div>
</aside>
