<?php

$secciones = [
  [
    'nombre' => '',
    'icono' => 'solar:menu-dots-linear',
    'enlaces' => [
      [
        'nombre' => 'Inicio',
        'icono' => 'solar:atom-line-duotone',
        'url' => './',
      ],
      [
        'nombre' => 'Inventorio',
        'icono' => 'solar:bill-list-broken',
        'url' => './inventario',
      ],
      // [
      //   'nombre' => 'Front Pages',
      //   'icono' => 'solar:home-angle-line-duotone',
      //   'subenlaces' => [
      //     [
      //       'nombre' => 'Homepage',
      //       'url' => 'https://bootstrapdemos.wrappixel.com/materialM/dist/main/frontend-landingpage.html',
      //     ],
      //   ],
      // ],
    ],
  ],
];

?>

<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="./" class="text-nowrap logo-img">
        <img src="./recursos/imagenes/logo-horizontal.png" class="img-fluid" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <?php foreach ($secciones as $indiceDeSeccion => $seccion): ?>
          <li class="nav-small-cap">
            <iconify-icon icon="<?= $seccion['icono'] ?>" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu"><?= $seccion['nombre'] ?></span>
          </li>
          <?php foreach ($seccion['enlaces'] as $enlace): ?>
            <?php if (empty($enlace['subenlaces'])): ?>
              <li class="sidebar-item">
                <a class="sidebar-link" href="<?= $enlace['url'] ?>" aria-expanded="false">
                  <iconify-icon icon="<?= $enlace['icono'] ?>"></iconify-icon>
                  <span class="hide-menu"><?= $enlace['nombre'] ?></span>
                </a>
              </li>
            <?php else: ?>
              <li class="sidebar-item">
                <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <div class="d-flex align-items-center gap-3">
                    <span class="d-flex">
                      <iconify-icon icon="<?= $enlace['icono'] ?>"></iconify-icon>
                    </span>
                    <span class="hide-menu"><?= $enlace['nombre'] ?></span>
                  </div>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <?php foreach ($enlace['subenlaces'] as $subenlace): ?>
                    <li class="sidebar-item">
                      <a class="sidebar-link justify-content-between" target="_blank"
                        href="<?= $subenlace['url'] ?>">
                        <div class="d-flex align-items-center gap-3">
                          <span class="d-flex">
                            <span class="icon-small"></span>
                          </span>
                          <span class="hide-menu"><?= $subenlace['nombre'] ?></span>
                        </div>
                      </a>
                    </li>
                  <?php endforeach ?>
                </ul>
              </li>
            <?php endif ?>
          <?php endforeach ?>
          <?php if ($indiceDeSeccion < count($secciones)): ?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
          <?php endif ?>
        <?php endforeach ?>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
