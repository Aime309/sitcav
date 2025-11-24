<?php

$colores = [
  [
    'id' => 'Blue_Theme',
    'value' => 'Blue_Theme',
    'color' => '#00a1ff',
    'title' => 'Tema azul',
  ],
  [
    'id' => 'Aqua_Theme',
    'value' => 'Aqua_Theme',
    'color' => '#0074ba',
    'title' => 'Tema aqua',
  ],
  [
    'id' => 'Purple_Theme',
    'value' => 'Purple_Theme',
    'color' => '#763ebd',
    'title' => 'Tema púrpura',
  ],
  [
    'id' => 'Green_Theme',
    'value' => 'Green_Theme',
    'color' => '#0a7ea4',
    'title' => 'Tema verde',
  ],
  [
    'id' => 'Cyan_Theme',
    'value' => 'Cyan_Theme',
    'color' => '#01c0c8',
    'title' => 'Tema cyan',
  ],
  [
    'id' => 'Orange_Theme',
    'value' => 'Orange_Theme',
    'color' => '#fa896b',
    'title' => 'Tema naranja',
  ],
];

$temas = [
  [
    'id' => 'light-layout',
    'title' => 'Claro',
    'icon' => 'bi bi-sun',
    'value' => 'light',
  ],
  [
    'id' => 'dark-layout',
    'title' => 'Oscuro',
    'icon' => 'bi bi-moon',
    'value' => 'dark',
  ],
];

$direcciones = [
  [
    'id' => 'ltr-layout',
    'title' => 'LTR',
    'icon' => 'bi bi-text-left',
    'value' => 'ltr',
  ],
  [
    'id' => 'rtl-layout',
    'title' => 'RTL',
    'icon' => 'bi bi-text-right',
    'value' => 'rtl',
  ],
];

$layouts = [
  [
    'id' => 'vertical-layout',
    'title' => 'Vertical',
    'icon' => 'bi bi-layout-sidebar',
    'value' => 'vertical',
  ],
  [
    'id' => 'horizontal-layout',
    'title' => 'Horizontal',
    'icon' => 'bi bi-window',
    'value' => 'horizontal',
  ],
];

$containers = [
  [
    'id' => 'boxed-layout',
    'title' => 'Tamaño limitado',
    'icon' => 'bi bi-distribute-horizontal',
    'value' => 'boxed',
  ],
  [
    'id' => 'full-layout',
    'title' => 'Pantalla completa',
    'icon' => 'bi bi-distribute-vertical',
    'value' => 'full',
  ],
];

$tiposMenu = [
  [
    'id' => 'full-sidebar',
    'title' => 'Completo',
    'icon' => 'bi bi-arrows-fullscreen',
    'value' => 'full',
  ],
  [
    'id' => 'mini-sidebar',
    'title' => 'Simplificado',
    'icon' => 'bi bi-arrows-collapse-vertical',
    'value' => 'mini-sidebar',
  ],
];

$tiposTarjeta = [
  [
    'id' => 'card-with-border',
    'title' => 'Con bordes',
    'icon' => 'bi bi-border',
    'value' => 'border',
  ],
  [
    'id' => 'card-without-border',
    'title' => 'Con sombras',
    'icon' => 'bi bi-shadows',
    'value' => 'shadow',
  ],
];

$id = uniqid();

$mostrarTema ??= true;
$mostrarColores ??= true;
$mostrarLayouts ??= true;
$mostrarContenedores ??= true;
$mostrarTiposMenu ??= true;

?>

<button
  class="btn rounded-circle position-fixed bottom-0 mb-3"
  :class="{
    'btn-light': tema === 'dark',
    'btn-dark': tema === 'light',
    'start-0 ms-3': direccion === 'rtl',
    'end-0 me-3': direccion === 'ltr',
  }"
  type="button"
  data-bs-toggle="offcanvas"
  data-bs-target="#<?= $id ?>"
  style="aspect-ratio: 1/1">
  <i class="bi bi-gear-fill"></i>
</button>

<div
  class="offcanvas text-start"
  :class="{
    'offcanvas-start': direccion === 'rtl',
    'offcanvas-end': direccion === 'ltr',
  }"
  id="<?= $id ?>">
  <div class="offcanvas-header border-bottom d-flex justify-content-between align-items-center">
    <h2 class="offcanvas-title">Configuraciones</h2>
    <button type="button" class="btn-close m-0" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-grid align-content-start">
    <?php if ($mostrarTema): ?>
      <section>
        <h3>Tema</h3>
        <div class="btn-group w-100 flex-wrap">
          <?php foreach ($temas as $tema): ?>
            <input
              class="btn-check"
              id="<?= $tema['id'] ?>"
              name="theme-layout"
              type="radio"
              value="<?= $tema['value'] ?>"
              x-model="tema" />
            <label
              class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-3 rounded-0"
              for="<?= $tema['id'] ?>">
              <i class="<?= $tema['icon'] ?>"></i>
              <?= $tema['title'] ?>
            </label>
          <?php endforeach ?>
        </div>
      </section>
      <hr />
    <?php endif ?>

    <?php if ($mostrarColores): ?>
      <section>
        <h3>Tema de Colores</h3>
        <div class="btn-group w-100 flex-wrap">
          <?php foreach ($colores as $color): ?>
            <input
              class="btn-check"
              id="<?= $color['id'] ?>"
              name="color-theme-layout"
              type="radio"
              value="<?= $color['value'] ?>"
              x-model="tema_colores" />
            <label
              class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-3 rounded-0"
              for="<?= $color['id'] ?>"
              data-bs-toggle="tooltip"
              data-bs-title="<?= $color['title'] ?>">
              <div
                class="text-light-emphasis rounded-circle d-flex align-items-center justify-content-center ratio ratio-1x1"
                style="background: <?= $color['color'] ?>">
              </div>
            </label>
          <?php endforeach ?>
        </div>
      </section>
      <hr />
    <?php endif ?>

    <?php if ($mostrarLayouts): ?>
      <section>
        <h3>Posición de la navegación</h3>
        <div class="btn-group w-100 flex-wrap">
          <?php foreach ($layouts as $layout): ?>
            <input
              class="btn-check"
              id="<?= $layout['id'] ?>"
              name="page-layout"
              type="radio"
              value="<?= $layout['value'] ?>"
              x-model="layout" />
            <label
              class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-3 rounded-0"
              for="<?= $layout['id'] ?>">
              <i class="<?= $layout['icon'] ?>"></i>
              <?= $layout['title'] ?>
            </label>
          <?php endforeach ?>
        </div>
      </section>
      <hr />
    <?php endif ?>

    <?php if ($mostrarContenedores): ?>
      <section>
        <h3>Anchura de la página</h3>
        <div class="btn-group w-100">
          <?php foreach ($containers as $container): ?>
            <input
              class="btn-check"
              id="<?= $container['id'] ?>"
              name="layout"
              type="radio"
              value="<?= $container['value'] ?>"
              x-model="container" />
            <label
              class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-3 rounded-0"
              for="<?= $container['id'] ?>">
              <i class="<?= $container['icon'] ?>"></i>
              <?= $container['title'] ?>
            </label>
          <?php endforeach ?>
        </div>
      </section>
      <hr />
    <?php endif ?>

    <?php if ($mostrarTiposMenu): ?>
      <section
        :class="{ 'disabled opacity-25': noHayNavs }"
        :style="noHayNavs && 'pointer-events: none'">
        <h3>Tipo de menú de navegación</h3>
        <div class="btn-group w-100 flex-wrap">
          <?php foreach ($tiposMenu as $tipoMenu): ?>
            <input
              class="btn-check"
              id="<?= $tipoMenu['id'] ?>"
              name="sidebar-type"
              type="radio"
              value="<?= $tipoMenu['value'] ?>"
              x-model="tipo_menu" />
            <label
              class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-3 rounded-0"
              for="<?= $tipoMenu['id'] ?>">
              <i class="<?= $tipoMenu['icon'] ?>"></i>
              <?= $tipoMenu['title'] ?>
            </label>
          <?php endforeach ?>
        </div>
      </section>
    <?php endif ?>
  </div>
</div>
