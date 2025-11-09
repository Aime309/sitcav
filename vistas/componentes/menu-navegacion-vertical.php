<?php

$miniNavItems = [
  [
    [
      'id' => 'mini-1',
      'tooltip' => 'Dashboards',
      'icon' => 'solar:layers-line-duotone',
    ],
  ]
];

$sidebarNavs = [
  [
    'id' => 'menu-right-mini-1',
    'grupos' => [
      [
        'nombre' => 'Dashboards',
        'enlaces' => [
          [
            'href' => '../main/index2.html',
            'icon' => 'solar:widget-add-line-duotone',
            'texto' => 'eCommerce',
          ],
          // [
          //   'href' => 'javascript:void(0)',
          //   'icon' => 'solar:home-angle-line-duotone',
          //   'texto' => 'Front Pages',
          //   'subenlaces' => [
          //     [
          //       'href' => '../main/frontend-landingpage.html',
          //       'texto' => 'Homepage',
          //     ],
          //     [
          //       'href' => '../main/frontend-landingpage-2.html',
          //       'texto' => '1.1',
          //       'subenlaces' => [
          //         [
          //           'href' => '../main/frontend-landingpage-2.html#section-features',
          //           'texto' => '2.1',
          //           'subenlaces' => [
          //             [
          //               'href' => '../main/frontend-landingpage-2.html#section-features',
          //               'texto' => '3.1',
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
];

?>

<aside class="side-mini-panel with-vertical">
  <div class="iconbar">
    <div class="mini-nav">
      <div class="brand-logo d-flex align-items-center justify-content-between justify-content-lg-center">
        <a href="./" class="logo-img">
          <img src="./recursos/imagenes/favicon.png" class="img-fluid" />
        </a>
        <button class="sidebartoggler btn-close close-btn d-xl-none"></button>
      </div>
      <ul class="list-unstyled mini-nav-ul overflow-y-auto overflow-x-hidden">
        <?php foreach ($miniNavItems as $links): ?>
          <?php foreach ($links as $link): ?>
            <li class="mini-nav-item" id="<?= $link['id'] ?>">
              <a
                href="javascript:void(0)"
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
      <ul class="list-unstyled mt-auto mb-4">
        <li class="dropup">
          <a href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="d-flex align-items-center justify-content-center gap-2 lh-base hover-border p-1 mx-auto rounded-circle" width="35">
              <img src="./recursos/imagenes/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="MaterialM-img" />
            </div>
          </a>
          <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up shadow-lg" aria-labelledby="drop1">
            <div class="profile-dropdown position-relative" data-simplebar>
              <div class="py-3 px-7 pb-0">
                <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
              </div>
              <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                <img src="./recursos/imagenes/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="MaterialM-img" />
                <div class="ms-3">
                  <h5 class="mb-0 fs-4">Jonathan Deo</h5>
                  <span class="mb-1 d-block">Admin</span>
                  <p class="mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-mail fs-4"></i> info@MaterialM.com
                  </p>
                </div>
              </div>
              <div class="message-body">
                <a href="../main/page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                  <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                    <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7"></iconify-icon>
                  </span>
                  <div class="w-75 v-middle ps-3">
                    <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                    <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                  </div>
                </a>
                <a href="../main/app-email.html" class="py-8 px-7 d-flex align-items-center">
                  <span class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                    <iconify-icon icon="solar:inbox-line-duotone" class="fs-7"></iconify-icon>
                  </span>
                  <div class="w-75 v-middle ps-3">
                    <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                    <span class="fs-2 d-block text-body-secondary">Messages & Emails</span>
                  </div>
                </a>
                <a href="../main/app-notes.html" class="py-8 px-7 d-flex align-items-center">
                  <span class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                    <iconify-icon icon="solar:checklist-minimalistic-line-duotone" class="fs-7"></iconify-icon>
                  </span>
                  <div class="w-75 v-middle ps-3">
                    <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                    <span class="fs-2 d-block text-body-secondary">To-do and Daily Tasks</span>
                  </div>
                </a>
              </div>
              <div class="d-grid py-4 px-7 pt-8">
                <a href="../main/authentication-login.html" class="btn btn-primary">Log Out</a>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="sidebarmenu">
      <?php foreach ($sidebarNavs as $nav): ?>
        <nav class="sidebar-nav overflow-y-auto" id="<?= $nav['id'] ?>">
          <ul class="list-unstyled sidebar-menu" id="sidebarnav">
            <?php foreach ($nav['grupos'] as $grupo): ?>
              <li class="nav-small-cap">
                <span class="hide-menu"><?= $grupo['nombre'] ?></span>
              </li>
              <?php foreach ($grupo['enlaces'] as $enlace): ?>
                <li class="sidebar-item">
                  <a
                    class="sidebar-link <?= count($enlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                    href="<?= $enlace['href'] ?>">
                    <iconify-icon icon="<?= $enlace['icon'] ?>" class=""></iconify-icon>
                    <span class="hide-menu"><?= $enlace['texto'] ?></span>
                  </a>
                  <ul class="collapse first-level">
                    <?php foreach ($enlace['subenlaces'] ?? [] as $subenlace): ?>
                      <li class="sidebar-item">
                        <a
                          class="sidebar-link <?= count($subenlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                          href="<?= count($subenlace['subenlaces'] ?? []) ? 'javascript:void(0)' : $subenlace['href'] ?>">
                          <span class="icon-small"></span>
                          <?= $subenlace['texto'] ?>
                        </a>
                        <ul class="collapse second-level">
                          <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                            <li class="sidebar-item">
                              <a
                                class="sidebar-link <?= count($subenlace['subenlaces'] ?? []) ? 'has-arrow' : '' ?>"
                                href="<?= count($subenlace['subenlaces'] ?? []) ? 'javascript:void(0)' : $subenlace['href'] ?>">
                                <span class="icon-small"></span>
                                <?= $subenlace['texto'] ?>
                              </a>
                              <ul class="collapse third-level">
                                <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                                  <li class="sidebar-item">
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
      <?php endforeach ?>
    </div>
  </div>
</aside>
