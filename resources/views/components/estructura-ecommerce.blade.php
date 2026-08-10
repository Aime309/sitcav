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
        <nav class="w3-container">
            <ul class="w3-bar">
                <li class="w3-bar-item">
                    <h1>
                        <a
                            href="{{ route('{negocio}', [
                                'negocio' => $negocio
                            ]) }}"
                            class="w3-button">
                            {{ $negocio->nombre }}
                        </a>
                    </h1>
                </li>
                <li class="w3-bar-item">
                    <a
                        href="{{ route('{negocio}.productos', [
                            'negocio' => $negocio
                        ]) }}"
                        class="w3-button">
                        Productos
                    </a>
                </li>
                @if (empty($usuario))
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.registrarse', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Registrarse
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.iniciar-sesion', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Iniciar sesión
                        </a>
                    </li>
                @else
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.cerrar-sesion', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Cerrar sesión
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.perfil', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Editar perfil
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.carrito', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Carrito
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="{{ route('{negocio}.reservas', [
                                'negocio' => $negocio['slug'],
                            ]) }}"
                            class="w3-button">
                            Reservas
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        {{ $slot }}
    </body>
</html>
