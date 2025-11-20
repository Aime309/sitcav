<?php

use SITCAV\Enums\ClaveSesion;
use SITCAV\Enums\Permiso;

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';
$errores = (array) session()->retrieve(ClaveSesion::MENSAJES_ERRORES->name, flash()->display(ClaveSesion::MENSAJES_ERRORES->name));
$exitos = (array) session()->retrieve(ClaveSesion::MENSAJES_EXITOS->name, flash()->display(ClaveSesion::MENSAJES_EXITOS->name));
$advertencias = (array) session()->retrieve(ClaveSesion::MENSAJES_ADVERTENCIAS->name, flash()->display(ClaveSesion::MENSAJES_ADVERTENCIAS->name));
$informaciones = (array) session()->retrieve(ClaveSesion::MENSAJES_INFORMACIONES->name, flash()->display(ClaveSesion::MENSAJES_INFORMACIONES->name));

/** @param array{permisos: Permiso[]} $enlace */
function tienePermisos(array $enlace): bool
{
  return !$enlace['permisos'] || auth()->user()?->can(array_map(static fn(Permiso $permiso): string => $permiso->name, $enlace['permisos']));
}

define('GRUPOS_ENLACES_NAVEGACION', [
  [
    [
      'tooltip' => 'Estadísticas',
      'icon' => 'bi bi-stack',
      'href' => './',
      'permisos' => [],
      'activo' => Flight::request()->url === '/',
      'nav' => [
        'activo' => Flight::request()->url === '/',
        'permisos' => [],
        'grupos' => [
          [
            'nombre' => 'Estadísticas',
            'enlaces' => [
              [
                'href' => './',
                'icon' => 'bi bi-shop',
                'texto' => 'Comercio electrónico',
                'permisos' => [],
              ],
              // [
              //   'href' => 'javascript:void(0)',
              //   'icon' => 'solar:home-angle-line-duotone',
              //   'texto' => 'Front Pages',
              //   'permisos' => [],
              //   'subenlaces' => [
              //     [
              //       'href' => '../main/frontend-landingpage.html',
              //       'texto' => 'Homepage',
              //       'permisos' => [],
              //     ],
              //     [
              //       'href' => '../main/frontend-landingpage-2.html',
              //       'texto' => '1.1',
              //       'permisos' => [],
              //       'subenlaces' => [
              //         [
              //           'href' => '../main/frontend-landingpage-2.html#section-features',
              //           'texto' => '2.1',
              //           'permisos' => [],
              //           'subenlaces' => [
              //             [
              //               'href' => '../main/frontend-landingpage-2.html#section-features',
              //               'texto' => '3.1',
              //               'permisos' => [],
              //             ],
              //           ],
              //         ],
              //       ],
              //     ],
              //   ],
              // ],
            ],
          ]
        ],
      ],
    ],
    [
      'tooltip' => 'Empleados',
      'icon' => 'bi bi-people',
      'href' => './empleados',
      'permisos' => [Permiso::VER_EMPLEADOS],
      'activo' => Flight::request()->url === '/empleados',
      'nav' => [
        'activo' => Flight::request()->url === '/empleados',
        'permisos' => [Permiso::VER_EMPLEADOS],
        'grupos' => [
          [
            'nombre' => 'Empleados',
            'enlaces' => [
              [
                'href' => './empleados',
                'icon' => 'bi bi-people',
                'texto' => 'Ver empleados',
                'permisos' => [Permiso::VER_EMPLEADOS],
              ],
              // [
              //   'href' => '#buscar-empleado',
              //   'icon' => 'bi bi-search',
              //   'texto' => 'Buscar empleado',
              //   'permisos' => [Permiso::VER_DETALLES_EMPLEADO],
              // ],
              // [
              //   'href' => './empleados/registrar',
              //   'icon' => 'bi bi-person-plus',
              //   'texto' => 'Registrar empleado',
              //   'permisos' => [Permiso::REGISTRAR_EMPLEADO],
              // ],
              // [
              //   'href' => './empleados/restablecer-clave',
              //   'icon' => 'bi bi-unlock',
              //   'texto' => 'Restablecer contraseña de un empleado',
              //   'permisos' => [Permiso::RESTABLECER_CLAVE_EMPLEADO],
              // ],
              // [
              //   'href' => './empleados/despedir',
              //   'icon' => 'bi bi-person-dash',
              //   'texto' => 'Despedir empleado',
              //   'permisos' => [Permiso::DESPEDIR_EMPLEADO],
              // ],
              // [
              //   'href' => './empleados/recontratar',
              //   'icon' => 'bi bi-person-check',
              //   'texto' => 'Recontratar empleado',
              //   'permisos' => [Permiso::RECONTRATAR_EMPLEADO],
              // ],
              // [
              //   'href' => './empleados/promover',
              //   'icon' => 'bi bi-person-up',
              //   'texto' => 'Promover vendedor',
              //   'permisos' => [Permiso::PROMOVER_VENDEDOR],
              // ],
              // [
              //   'href' => './empleados/degradar',
              //   'icon' => 'bi bi-person-down',
              //   'texto' => 'Degradar empleado superior',
              //   'permisos' => [Permiso::DEGRADAR_EMPLEADO_SUPERIOR],
              // ],
            ],
          ]
        ],
      ],
    ],
    [
      'tooltip' => 'Inventario',
      'icon' => 'bi bi-box-seam',
      'href' => './inventario',
      'permisos' => [Permiso::VER_PRODUCTOS],
      'activo' => Flight::request()->url === '/inventario',
      'nav' => [
        'activo' => Flight::request()->url === '/inventario',
        'permisos' => [Permiso::VER_PRODUCTOS],
        'grupos' => [
          [
            'nombre' => 'Inventario',
            'enlaces' => [
              [
                'href' => './inventario',
                'icon' => 'bi bi-people',
                'texto' => 'Ver productos',
                'permisos' => [Permiso::VER_PRODUCTOS],
              ],
            ],
          ]
        ],
      ],
    ],
    // [
    //   'tooltip' => 'Ventas',
    //   'icon' => 'bi bi-currency-dollar',
    //   'permisos' => [Permiso::VER_VENTAS],
    // ],
    // [
    //   'tooltip' => 'Negocios',
    //   'icon' => 'bi bi-briefcase',
    //   'permisos' => [Permiso::VER_NEGOCIOS],
    // ],
    // [
    //   'tooltip' => 'Proveedores',
    //   'icon' => 'bi bi-truck',
    //   'permisos' => [Permiso::VER_PROVEEDORES],
    // ],
    // [
    //   'tooltip' => 'Compras',
    //   'icon' => 'bi bi-cart4',
    //   'permisos' => [Permiso::VER_COMPRAS],

    // ],
    // [
    //   'tooltip' => 'Clientes',
    //   'icon' => 'bi bi-person-lines-fill',
    //   'permisos' => [Permiso::VER_CLIENTES],
    // ],
    // [
    //   'tooltip' => 'Pagos',
    //   'icon' => 'bi bi-credit-card-2-front',
    //   'permisos' => [Permiso::VER_PAGOS],
    // ],
    // [
    //   'tooltip' => 'Configuraciones',
    //   'icon' => 'bi bi-gear',
    //   'permisos' => [],
    // ],
  ]
]);

?>

<!doctype html>
<html
  dir="<?= session()->get(ClaveSesion::UI_DIRECCION->name, 'ltr') ?>"
  data-layout="<?= session()->get(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, 'vertical') ?>"
  data-bs-theme="<?= session()->get(ClaveSesion::UI_TEMA->name, '') ?>"
  data-color-theme="<?= session()->get(ClaveSesion::UI_COLORES->name, 'Blue_Theme') ?>"
  data-boxed-layout="<?= session()->get(ClaveSesion::UI_ANCHURA->name, 'boxed') ?>"
  data-sidebartype="<?= session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'mini-sidebar') ?>"
  data-card="<?= session()->get(ClaveSesion::UI_TIPO_TARJETAS->name, 'shadow') ?>"
  x-data="tema"
  :dir="direccion"
  :data-layout="layout"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores"
  :data-boxed-layout="container"
  :data-sidebartype="tipo_menu"
  :data-card="tipo_tarjeta">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link
    rel="stylesheet"
    href="./recursos/compilados/autenticados.css?id=<?= $idDeRecursos ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= $idDeRecursos ?>" />
</head>

<body
  data-sidebartype="<?= session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'full') ?>"
  data-errores='<?= json_encode(array_values($errores)) ?>'
  data-exitos='<?= json_encode(array_values($exitos)) ?>'
  data-advertencias='<?= json_encode(array_values($advertencias)) ?>'
  data-informaciones='<?= json_encode(array_values($informaciones)) ?>'
  x-data="mensajes"
  :data-sidebartype="tipo_menu">
  <?php Flight::render('componentes/notificaciones') ?>
  <div
    id="main-wrapper"
    x-data="{
      noHayNavs: false,
      productosEnCarrito: {},
    }"
    x-effect="
      if (noHayNavs) {
        tipo_menu = 'mini-sidebar';
      }
    ">
    <?php Flight::render('componentes/configuraciones-ui') ?>
    <?php Flight::render('componentes/menu-navegacion-vertical') ?>
    <div class="page-wrapper" x-data="tasaDePagina">
      <?php Flight::render('componentes/menu-superior-v2') ?>
      <?php Flight::render('componentes/menu-navegacion-horizontal') ?>
      <div class="body-wrapper">
        <main class="container-fluid">
          <?php if (!in_array(Flight::request()->url, ['/'])): ?>
            <header class="card card-header flex-row align-items-center justify-content-between">
              <h1 class="card-title m-0"><?= $titulo ?></h1>
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item d-flex align-items-center">
                  <a class="text-muted text-decoration-none d-flex" href="./">
                    <i class="bi bi-house"></i>
                  </a>
                </li>
                <li class="breadcrumb-item">
                  <span class="badge bg-primary-subtle text-primary">
                    <?= $titulo ?>
                  </span>
                </li>
              </ol>
            </header>
          <?php endif ?>
          <?= $pagina ?>
        </main>
      </div>
    </div>
  </div>

  <script src="./recursos/compilados/autenticados.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/theme.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/app.min.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/sidebarmenu.js?id=<?= $idDeRecursos ?>"></script>
</body>

</html>
