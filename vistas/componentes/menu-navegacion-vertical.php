<?php

use SITCAV\Enums\Permiso;

$miniNavItems = [
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
];

$sidebarNavs = array_map(
  static fn(array $grupo): array => $grupo['nav'] + ['mostrar' => !key_exists('href', $grupo)],
  array_reduce(
    $miniNavItems,
    static fn(array $carry, array $items): array => array_merge($carry, $items),
    [],
  )
);

/** @param array{permisos: Permiso[]} $enlace */
function tienePermisos(array $enlace): bool
{
  return !$enlace['permisos'] || auth()->user()?->can(array_map(static fn(Permiso $permiso): string => $permiso->name, $enlace['permisos']));
}

?>

<aside
  class="side-mini-panel with-vertical"
  <?= !array_all($sidebarNavs, static fn(array $nav): bool => $nav['mostrar']) ? 'x-init="noHayNavs = true"' : '' ?>>
  <div class="iconbar">
    <div class="mini-nav d-flex flex-column justify-content-between">
      <div class="brand-logo d-flex flex-column gap-3 align-items-center justify-content-between justify-content-lg-center">
        <a href="./" class="logo-img">
          <img src="./recursos/imagenes/favicon.png" class="img-fluid" />
        </a>
        <button class="sidebartoggler btn-close close-btn d-xl-none position-relative top-0 end-0"></button>
      </div>
      <ul class="list-unstyled mini-nav-ul overflow-y-auto overflow-x-hidden h-auto">
        <?php foreach ($miniNavItems as $links): ?>
          <?php foreach ($links as $indice => $link): ?>
            <li
              class="mini-nav-item <?= !tienePermisos($link) ? 'disabled opacity-25' : '' ?>"
              <?= !key_exists('href', $link) ? sprintf('id="mini-%d"', $indice + 1) : '' ?>
              style="<?= !tienePermisos($link) ? 'pointer-events: none' : '' ?>">
              <a
                class="<?= $link['activo'] ? 'text-bg-primary' : '' ?>"
                href="<?= key_exists('href', $link) ? $link['href'] : 'javascript:' ?>"
                data-bs-toggle="tooltip"
                data-bs-custom-class="custom-tooltip"
                data-bs-placement="right"
                data-bs-title="<?= $link['tooltip'] ?>">
                <i class="<?= $link['icon'] ?>"></i>
              </a>
            </li>
          <?php endforeach ?>
          <li>
            <span class="sidebar-divider lg"></span>
          </li>
        <?php endforeach ?>
      </ul>
      <ul class="list-unstyled">
        <li class="dropup" data-bs-toggle="tooltip" title="Perfil de usuario" data-bs-placement="right">
          <?php Flight::render('componentes/dropdown-avatar') ?>
        </li>
      </ul>
    </div>
    <div class="sidebarmenu">
      <?php foreach ($sidebarNavs as $indice => $nav): ?>
        <?php if (tienePermisos($nav) && $nav['mostrar']): ?>
          <nav
            class="sidebar-nav overflow-y-auto <?= !$indice ? 'd-block' : '' ?>"
            id="menu-right-mini-<?= $indice + 1 ?>">
            <ul class="list-unstyled sidebar-menu" id="sidebarnav">
              <?php foreach ($nav['grupos'] as $grupo): ?>
                <li class="nav-small-cap">
                  <span class="hide-menu"><?= $grupo['nombre'] ?></span>
                </li>
                <?php foreach ($grupo['enlaces'] as $enlace): ?>
                  <li
                    class="sidebar-item <?= !tienePermisos($enlace) ? 'disabled opacity-25' : '' ?>"
                    style="<?= !tienePermisos($enlace) ? 'pointer-events: none' : '' ?>">
                    <a
                      class="sidebar-link <?= count($enlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                      href="<?= $enlace['href'] ?>">
                      <i class="<?= $enlace['icon'] ?>"></i>
                      <span class="hide-menu text-wrap"><?= $enlace['texto'] ?></span>
                    </a>
                    <ul class="collapse first-level ps-1" style="list-style: none">
                      <?php foreach ($enlace['subenlaces'] ?? [] as $subenlace): ?>
                        <li
                          class="sidebar-item <?= !tienePermisos($subenlace) ? 'disabled opacity-25' : '' ?>"
                          style="<?= !tienePermisos($subenlace) ? 'pointer-events: none' : '' ?>">
                          <a
                            class="sidebar-link <?= count($subenlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                            href="<?= count($subenlace['subenlaces'] ?? []) ? 'javascript:void(0)' : $subenlace['href'] ?>">
                            <span class="icon-small"></span>
                            <?= $subenlace['texto'] ?>
                          </a>
                          <ul class="collapse second-level ps-1" style="list-style: none">
                            <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                              <li
                                class="sidebar-item <?= !tienePermisos($subenlace) ? 'disabled opacity-25' : '' ?>"
                                style="<?= !tienePermisos($subenlace) ? 'pointer-events: none' : '' ?>">
                                <a
                                  class="sidebar-link <?= count($subenlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                                  href="<?= count($subenlace['subenlaces'] ?? []) ? 'javascript:void(0)' : $subenlace['href'] ?>">
                                  <span class="icon-small"></span>
                                  <?= $subenlace['texto'] ?>
                                </a>
                                <ul class="collapse third-level ps-1" style="list-style: none">
                                  <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                                    <li
                                      class="sidebar-item <?= !tienePermisos($subenlace) ? 'disabled opacity-25' : '' ?>"
                                      style="<?= !tienePermisos($subenlace) ? 'pointer-events: none' : '' ?>">
                                      <a
                                        class="sidebar-link <?= count($subenlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                                        href="<?= count($subenlace['subenlaces'] ?? []) ? 'javascript:void(0)' : $subenlace['href'] ?>">
                                        <span class="icon-small"></span>
                                        <?= $subenlace['texto'] ?>
                                      </a>
                                    </li>
                                  <?php endforeach ?>
                                </ul>
                              </li>
                            <?php endforeach ?>
                          </ul>
                        </li>
                      <?php endforeach ?>
                    </ul>
                  </li>
                <?php endforeach ?>
                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
              <?php endforeach ?>
            </ul>
          </nav>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
</aside>
