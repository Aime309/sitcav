@php

$nombreEstablecimiento = match (true) {
    !empty($sucursal) => $sucursal->nombre,
    !empty($negocio) => $negocio->nombre,
    default => '',
};

@endphp

<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data='{
        tema: $persist(matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light"),
    }'
    x-init='
        matchMedia("(prefers-color-scheme: dark)").addEventListener(
            "change",
            event => {
                tema = event.matches ? "dark" : "light";
            },
        );
    '
    x-bind:data-theme="tema">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta name="color-scheme" content="light dark" />
        <title>{{ $titulo }} - {{ config('app.name') }}</title>
        <base href="{{ str_replace(
            'index.php',
            '',
            $_SERVER['SCRIPT_NAME']
        ) }}" />
        <link rel="icon" href="./favicon.png" />
        @fonts
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
            'resources/scss/index.scss',
        ])
    </head>
    <body>
        <div class="shell">
            <x-panel.barra-lateral
                :usuario="$usuario"
                :negocio="$negocio"
                :sucursal="$sucursal ?? null" />
            <div class="main">
                <x-panel.barra-superior
                    :crumbs="[$nombreEstablecimiento, ...$crumbs ?? []]"
                    :negocio="$negocio"
                    :usuario="$usuario" />
                <main class="content">
                    {{ $slot }}
                </main>
                <x-panel.pie-pagina />
            </div>
        </div>
    </body>
</html>
