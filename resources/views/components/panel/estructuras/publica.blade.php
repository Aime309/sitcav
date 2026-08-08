<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data='{
        tema: matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light",
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
        {{ $slot }}
    </body>
</html>
