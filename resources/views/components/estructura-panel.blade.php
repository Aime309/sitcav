<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data='{
        tema: matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light",
    }'
    x-init='
        matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (event) => {
            tema = event.matches ? "dark" : "light";
        });
    '>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta name="color-scheme" content="light dark" />
        <title>{{ $titulo }} - {{ config('app.name') }}</title>
        <base href="{{ str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) }}" />
        <link rel="icon" href="./favicon.png" />
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link
            rel="stylesheet"
            href="https://www.w3schools.com/w3css/5/w3.css"
        />
        <script
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.12/dist/cdn.min.js"
            defer>
        </script>
    </head>
    <body>
        @if (!empty($negocio))
            <nav class="w3-container">
                <ul class="w3-bar">
                    <li class="w3-bar-item">
                        @if (empty($sucursal))
                            <h1>
                                <a
                                    href="{{ route('panel.negocios.{negocio}', [
                                        'negocio' => $negocio['id'],
                                    ]) }}"
                                    class="w3-button">
                                    {{ $negocio['nombre'] }}
                                </a>
                            </h1>
                        @else
                            <h1>
                                <a
                                    href="{{ route(
                                        'panel.negocios.{negocio}.sucursales.{sucursal}',
                                        [
                                            'negocio' => $negocio['id'],
                                            'sucursal' => $sucursal['id'],
                                        ],
                                    ) }}"
                                    class="w3-button">
                                    {{ $sucursal['nombre'] }}
                                </a>
                            </h1>
                        @endif
                    </li>
                    @if ($usuario->roles->contains('rol', 'administrador'))
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.empleados',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Empleados
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.sucursales',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Sucursales
                            </a>
                        </li>
                    @endif
                    @if ($usuario->roles->contains('rol', 'encargado'))
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.proveedores',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Proveedores
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.clientes',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Clientes
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.compras',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Compras
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.ventas',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Ventas
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.reservas',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Reservas
                            </a>
                        </li>
                        <li class="w3-bar-item">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.inventario',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Inventario
                            </a>
                        </li>
                    @endif
                    <li class="w3-bar-item">
                        <a
                            href="{{ route(
                                'panel.negocios.{negocio}.productos',
                                ['negocio' => $negocio['id']],
                            ) }}"
                            class="w3-button">
                            Productos
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('panel.cerrar-sesion') }}"
                            class="w3-button">
                            Cerrar sesión
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route(
                                'panel.negocios.{negocio}.perfil',
                                ['negocio' => $negocio['id']],
                            ) }}"
                            class="w3-button">
                            Editar perfil
                        </a>
                    </li>
                    @if ($usuario->roles->contains('rol', 'administrador'))
                        <li class="w3-bar-item w3-right">
                            <a
                                href="{{ route(
                                    'panel.negocios.{negocio}.editar',
                                    ['negocio' => $negocio['id']],
                                ) }}"
                                class="w3-button">
                                Editar negocio
                            </a>
                        </li>
                        <li class="w3-bar-item w3-right">
                            <a
                                href="{{ route('panel.negocios') }}"
                                class="w3-button">
                                Seleccionar establecimiento
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif

        {{ $slot }}
    </body>
</html>
