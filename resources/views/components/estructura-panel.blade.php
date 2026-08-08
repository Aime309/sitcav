@php

$dataUsuario = [
    'iniciales' => '',
    'nombre' => '',
    'rol' => $usuario?->roles[0]?->rol ?? '',
    'correo' => $usuario?->correo ?? '',
];

$crumbs = match (true) {
    !empty($sucursal) => $sucursal->nombre,
    !empty($negocio) => $negocio->nombre,
    default => '',
};

$dataTopBar = [];

if (!empty($negocio)) {
    $dataTopBar['reservas'] = route(
        'panel.negocios.{negocio}.reservas',
        ['negocio' => $negocio],
    );

    $dataTopBar['perfil'] = route(
        'panel.negocios.{negocio}.perfil',
        ['negocio' => $negocio],
    );

    $dataTopBar['cerrarSesion'] = route('panel.cerrar-sesion');

    if ($usuario?->roles?->contains('rol', 'administrador')) {
        $dataTopBar['configuraciones'] = route(
            'panel.negocios.{negocio}.editar',
            ['negocio' => $negocio],
        );

        $dataTopBar['negocios'] = route('panel.negocios');
    }
}

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
    x-bind:data-theme="tema"
    data-app-name="{{ config('app.name') }}"
    data-usuario='@json($dataUsuario)'
    data-topbar='@json($dataTopBar)'>
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
    <body data-crumbs="{{ $crumbs ?? '' }}">
        <div class="shell">
            <x-panel.barra-lateral
                :usuario="$usuario"
                :negocio="$negocio" />
            <div class="main">
                <div data-shell-topbar></div>
                <main class="content">
                    {{ $slot }}
                </main>
                <x-panel.pie-pagina />
            </div>
        </div>
    </body>
</html>
