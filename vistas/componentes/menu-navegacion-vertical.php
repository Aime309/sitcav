<?php

$sidebarNavs = array_map(
  static fn(array $grupo): array => $grupo['nav'] + ['mostrar' => !key_exists('href', $grupo)],
  array_reduce(
    GRUPOS_ENLACES_NAVEGACION,
    static fn(array $carry, array $items): array => array_merge($carry, $items),
    [],
  )
);

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
        <?php foreach (GRUPOS_ENLACES_NAVEGACION as $links): ?>
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
