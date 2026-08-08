@php

$dataUsuario = [
    'iniciales' => '',
    'nombre' => '',
    'rol' => $usuario?->roles[0]?->rol ?? '',
    'correo' => $usuario?->correo ?? '',
];

$dataNav = [
    [
        'label' => 'Espacio de trabajo',
        'items' => [
            [
                'key' => 'dashboard',
                'text' => 'Inicio',
                'href' => match (true) {
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
                },
                'icon' => '<path d="M3 12 12 3l9 9"/><path d="M5 10v10h14V10"/>',
            ],
            // [
            //     'key' => 'docs',
            //     'text' => 'Documentation',
            //     'href' => 'https =>//adminator.colorlib.com/docs/',
            //     'badge' => [
            //         'kind' => 'new',
            //         'text' => 'DOCS',
            //     ],
            //     'icon' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'
            // ],
            // [
            //     'key' => 'pro',
            //     'text' => 'Go Pro',
            //     'href' => 'https =>//dashboardpack.com/?utm_source=colorlib&utm_medium=adminator-demo&utm_campaign=sidebar-go-pro',
            //     'badge' => [
            //         'kind' => 'pro',
            //         'text' => 'PRO',
            //     ],
            //     'icon' => '<path d="M12 2 15 8l6.5 1-4.8 4.6L18 20l-6-3-6 3 1.3-6.4L2.5 9 9 8z"/>'
            // ],
        ],
    ],
    // [
    //     'label' => 'Communications',
    //     'items' => [
    //         [
    //             'key' => 'email',
    //             'text' => 'Email',
    //             'href' => 'email.html',
    //             'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>'
    //         ],
    //         [
    //             'key' => 'compose',
    //             'text' => 'Compose',
    //             'href' => 'compose.html',
    //             'icon' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/>'
    //         ],
    //         [
    //             'key' => 'calendar',
    //             'text' => 'Calendar',
    //             'href' => 'calendar.html',
    //             'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'
    //         ],
    //         [
    //             'key' => 'chat',
    //             'text' => 'Chat',
    //             'href' => 'chat.html',
    //             'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'
    //         ],
    //     ],
    // ],
    // [
    //     'label' => 'Components',
    //     'items' => [
    //         [
    //             'key' => 'charts',
    //             'text' => 'Charts',
    //             'href' => 'charts.html',
    //             'badge' => [
    //                 'kind' => 'new',
    //                 'text' => 'NEW',
    //             ],
    //             'icon' => '<path d="M3 20V4M7 20v-6M11 20v-10M15 20v-4M19 20V8"/>'
    //         ],
    //         [
    //             'key' => 'forms',
    //             'text' => 'Forms',
    //             'href' => 'forms.html',
    //             'icon' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 10h10M7 14h7"/>'
    //         ],
    //         [
    //             'key' => 'ui',
    //             'text' => 'UI Elements',
    //             'href' => 'ui.html',
    //             'icon' => '<circle cx="12" cy="12" r="9"/><path d="M8 12h8M12 8v8"/>'
    //         ],
    //         [
    //             'key' => 'buttons',
    //             'text' => 'Buttons',
    //             'href' => 'buttons.html',
    //             'icon' => '<rect x="3" y="8" width="18" height="8" rx="4"/>'
    //         ],
    //         [
    //             'key' => 'tables',
    //             'text' => 'Tables',
    //             'icon' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M3 16h18M9 4v16"/>',
    //             'children' => [
    //                 [
    //                     'key' => 'basic-table',
    //                     'text' => 'Basic Table',
    //                     'href' => 'basic-table.html',
    //                 ],
    //                 [
    //                     'key' => 'datatable',
    //                     'text' => 'Data Table',
    //                     'href' => 'datatable.html',
    //                 ],
    //             ],
    //         ],
    //         [
    //             'key' => 'maps',
    //             'text' => 'Maps',
    //             'icon' => '<path d="M9 20V4l6 4v16z"/><path d="M3 7l6-3v16l-6 3z"/><path d="M15 8l6-3v16l-6 3"/>',
    //             'children' => [
    //                 [
    //                     'key' => 'google-maps',
    //                     'text' => 'Google Map',
    //                     'href' => 'google-maps.html',
    //                 ],
    //                 [
    //                     'key' => 'vector-maps',
    //                     'text' => 'Vector Map',
    //                     'href' => 'vector-maps.html',
    //                 ],
    //             ],
    //         ],
    //         [
    //             'key' => 'pages',
    //             'text' => 'Pages',
    //             'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>',
    //             'children' => [
    //                 [
    //                     'key' => 'blank',
    //                     'text' => 'Blank',
    //                     'href' => 'blank.html',
    //                 ],
    //                 [
    //                     'key' => '404',
    //                     'text' => '404',
    //                     'href' => '404.html',
    //                 ],
    //                 [
    //                     'key' => '500',
    //                     'text' => '500',
    //                     'href' => '500.html',
    //                 ],
    //                 [
    //                     'key' => 'signin',
    //                     'text' => 'Sign In',
    //                     'href' => 'signin.html',
    //                 ],
    //                 [
    //                     'key' => 'signup',
    //                     'text' => 'Sign Up',
    //                     'href' => 'signup.html',
    //                 ],
    //             ],
    //         ],
    //     ],
    // ],
];

if (!empty($usuario) && $usuario?->roles?->contains('rol', 'administrador')) {
    $dataNav[0]['items'][] = [
        'key' => 'empleados',
        'text' => 'Empleados',
        'href' => route(
            'panel.negocios.{negocio}.empleados',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'sucursales',
        'text' => 'Sucursales',
        'href' => route(
            'panel.negocios.{negocio}.sucursales',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" />',
    ];
}

if (!empty($usuario) && $usuario?->roles?->contains('rol', 'encargado')) {
    $dataNav[0]['items'][] = [
        'key' => 'proveedores',
        'text' => 'Proveedores',
        'href' => route(
            'panel.negocios.{negocio}.proveedores',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M4 7h16" />
<path d="M4 12h16" />
<path d="M4 17h10" />
<rect x="17" y="12" width="3" height="5" rx="1" />
<path d="M21 17h-4" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'clientes',
        'text' => 'Clientes',
        'href' => route(
            'panel.negocios.{negocio}.clientes',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'compras',
        'text' => 'Compras',
        'href' => route(
            'panel.negocios.{negocio}.compras',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
<path d="M3 6h18" />
<path d="M16 10a4 4 0 0 1-8 0" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'ventas',
        'text' => 'Ventas',
        'href' => route(
            'panel.negocios.{negocio}.ventas',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M3 3v18h18" />
<path d="M7 14l4-4 4 4 6-6" />
<path d="M21 8v4h-4" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'reservas',
        'text' => 'Reservas',
        'href' => route(
            'panel.negocios.{negocio}.reservas',
            ['negocio' => $negocio],
        ),
        'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" />
<path d="M8 2v4" />
<path d="M16 2v4" />
<path d="M3 10h18" />
<path d="M12 14v4" />
<path d="M8 14h.01" />
<path d="M16 14h.01" />',
    ];

    $dataNav[0]['items'][] = [
        'key' => 'inventario',
        'text' => 'Inventario',
        'href' => route(
            'panel.negocios.{negocio}.inventario',
            ['negocio' => $negocio],
        ),
        'icon' => '<rect x="4" y="6" width="16" height="12" rx="2" />
<path d="M8 6v4" />
<path d="M16 6v4" />
<path d="M4 10h16" />
<path d="M8 14h8" />',
    ];
}

if (!empty($negocio)) {
    $dataNav[0]['items'][] = [
        'key' => 'productos',
        'text' => 'Productos',
        'href' => route(
            'panel.negocios.{negocio}.productos',
            ['negocio' => $negocio],
        ),
        'icon' => '<path d="M4 6h16v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
<path d="M4 6 6 3h12l2 3" />
<path d="M8 10h8" />
<path d="M8 14h6" />',
    ];
}

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
    data-nav='@json($dataNav)'
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
    <body
        data-crumbs="{{ $crumbs ?? '' }}"
        data-active="{{ $paginaActiva ?? '' }}">
        <div class="shell">
            <div data-shell-sidebar></div>
            <div class="main">
                <div data-shell-topbar></div>
                <main class="content">
                    {{ $slot }}
                </main>
                <div data-shell-footer></div>
            </div>
        </div>
    </body>
</html>
