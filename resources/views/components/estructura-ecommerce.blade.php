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
                            href="./{{ $negocio['slug'] }}"
                            class="w3-button">
                            {{ $negocio['nombre'] }}
                        </a>
                    </h1>
                </li>
                @if (empty($usuario))
                    <li class="w3-bar-item w3-right">
                        <a
                            href="./{{ $negocio['slug'] }}/registrarse"
                            class="w3-button">
                            Registrarse
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="./{{ $negocio['slug'] }}/iniciar-sesion"
                            class="w3-button">
                            Iniciar sesión
                        </a>
                    </li>
                @else
                    <li class="w3-bar-item w3-right">
                        <a
                            href="./{{ $negocio['slug'] }}/cerrar-sesion"
                            class="w3-button">
                            Cerrar sesión
                        </a>
                    </li>
                    <li class="w3-bar-item w3-right">
                        <a
                            href="./{{ $negocio['slug'] }}/perfil"
                            class="w3-button">
                            Editar perfil
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        {{ $slot }}
    </body>
</html>
