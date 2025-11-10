<?php

$enlaces = array_reduce(
  GRUPOS_ENLACES_NAVEGACION,
  static fn(array $carry, array $items): array => array_merge($carry, $items),
  [],
);

// TODO: no se está mostrando third-level

?>

<aside class="left-sidebar with-horizontal">
  <div>
    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
      <ul id="sidebarnav">
        <?php foreach ($enlaces as $enlace): ?>
          <li class="sidebar-item">
            <a
              class="sidebar-link <?= !key_exists('href', $enlace) ? 'has-arrow' : '' ?>"
              href="<?= $enlace['href'] ?? 'javascript:' ?>">
              <i class="<?= $enlace['icon'] ?>"></i>
              <span class="hide-menu"><?= $enlace['tooltip'] ?></span>
            </a>
            <?php if (!key_exists('href', $enlace)): ?>
              <ul class="list-unstyled collapse first-level">
                <?php foreach (($enlace['nav']['grupos'] ?? []) as $grupo): ?>
                  <?php foreach ($grupo['enlaces'] as $subenlace): ?>
                    <li class="sidebar-item">
                      <a
                        href="<?= $subenlace['href'] ?? 'javascript:' ?>"
                        class="sidebar-link <?= key_exists('subenlaces', $subenlace) ? 'has-arrow' : '' ?>">
                        <i class="<?= $subenlace['icon'] ?>"></i>
                        <span class="hide-menu"><?= $subenlace['texto'] ?></span>
                      </a>
                      <ul class="list-unstyled collapse second-level">
                        <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                          <li class="sidebar-item">
                            <a
                              href="<?= $subenlace['href'] ?? 'javascript:' ?>"
                              class="sidebar-link <?= key_exists('subenlaces', $subenlace) ? 'has-arrow' : '' ?>">
                              <i class="bi bi-circle"></i>
                              <span class="hide-menu"><?= $subenlace['texto'] ?></span>
                            </a>
                            <ul class="list-unstyled collapse third-level">
                              <?php foreach ($subenlace['subenlaces'] ?? [] as $subenlace): ?>
                                <li class="sidebar-item">
                                  <a href="<?= $subenlace['href'] ?? 'javascript:' ?>" class="sidebar-link">
                                    <i class="bi bi-circle"></i>
                                    <span class="hide-menu"><?= $subenlace['texto'] ?></span>
                                  </a>
                                </li>
                              <?php endforeach ?>
                            </ul>
                          </li>
                        <?php endforeach ?>
                      </ul>
                    </li>
                  <?php endforeach ?>
                <?php endforeach ?>
              </ul>
            <?php endif ?>
          </li>
        <?php endforeach ?>
      </ul>
    </nav>
  </div>
</aside>
