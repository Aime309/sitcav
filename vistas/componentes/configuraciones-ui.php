<?php

$colores = [
  [
    'id' => 'Blue_Theme',
    'class' => 'skin-1',
    'title' => 'BLUE_THEME',
  ],
  [
    'id' => 'Aqua_Theme',
    'class' => 'skin-2',
    'title' => 'AQUA_THEME',
  ],
  [
    'id' => 'Purple_Theme',
    'class' => 'skin-3',
    'title' => 'PURPLE_THEME',
  ],
  [
    'id' => 'Green_Theme',
    'class' => 'skin-4',
    'title' => 'GREEN_THEME',
  ],
  [
    'id' => 'Cyan_Theme',
    'class' => 'skin-5',
    'title' => 'CYAN_THEME',
  ],
  [
    'id' => 'Orange_Theme',
    'class' => 'skin-6',
    'title' => 'ORANGE_THEME',
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

$id = uniqid();

?>

<button
  class="btn btn-success rounded-circle customizer-btn"
  type="button"
  data-bs-toggle="offcanvas"
  data-bs-target="#<?= $id ?>"
  style="aspect-ratio: 1/1">
  <i class="bi bi-gear"></i>
</button>

<div class="offcanvas customizer offcanvas-end" id="<?= $id ?>">
  <div class="offcanvas-header">
    <h2 class="offcanvas-title">Configuraciones</h2>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-grid gap-3">
    <h3>Tema</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($temas as $tema): ?>
        <input
          class="btn-check"
          id="<?= $tema['id'] ?>"
          name="theme-layout"
          type="radio"
          value="<?= $tema['value'] ?>"
          x-model="tema" />
        <label
          class="btn btn-outline-primary"
          for="<?= $tema['id'] ?>">
          <i class="icon <?= $tema['icon'] ?> me-3"></i>
          <?= $tema['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h3>Dirección</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($direcciones as $direccion): ?>
        <input
          class="btn-check"
          id="<?= $direccion['id'] ?>"
          name="direction-l"
          type="radio"
          value="<?= $direccion['value'] ?>"
          x-model="direccion" />
        <label class="btn btn-outline-primary" for="<?= $direccion['id'] ?>">
          <i class="icon <?= $direccion['icon'] ?> me-2"></i>
          <?= $direccion['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h3>Tema de Colores</h3>

    <div class="d-flex flex-wrap gap-3 customizer-box color-pallete">
      <?php foreach ($colores as $color): ?>
        <input
          class="btn-check"
          id="<?= $color['id'] ?>"
          name="color-theme-layout"
          type="radio"
          value="<?= $color['id'] ?>"
          x-model="tema_colores" />
        <label
          class="btn btn-outline-primary d-flex align-items-center justify-content-center"
          for="<?= $color['id'] ?>"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          data-bs-title="<?= $color['title'] ?>">
          <div class="color-box rounded-circle d-flex align-items-center justify-content-center <?= $color['class'] ?>">
            <i class="bi bi-check text-white icon"></i>
          </div>
        </label>
      <?php endforeach ?>
    </div>

    <h3>Posición de la navegación</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($layouts as $layout): ?>
        <input
          class="btn-check"
          id="<?= $layout['id'] ?>"
          name="page-layout"
          type="radio"
          value="<?= $layout['value'] ?>"
          x-model="layout" />
        <label
          class="btn btn-outline-primary" for="<?= $layout['id'] ?>">
          <i class="icon <?= $layout['icon'] ?> me-2"></i>
          <?= $layout['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h3>Anchura</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($containers as $container): ?>
        <input
          class="btn-check"
          id="<?= $container['id'] ?>"
          name="layout"
          type="radio"
          value="<?= $container['value'] ?>"
          x-model="container" />
        <label
          class="btn btn-outline-primary" for="<?= $container['id'] ?>">
          <i class="icon <?= $container['icon'] ?> me-2"></i>
          <?= $container['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h3>Tipo de menú de navegación</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($tiposMenu as $tipoMenu): ?>
        <input
          class="btn-check"
          id="<?= $tipoMenu['id'] ?>"
          name="sidebar-type"
          type="radio"
          value="<?= $tipoMenu['value'] ?>"
          x-model="tipo_menu" />
        <label
          class="btn btn-outline-primary"
          for="<?= $tipoMenu['id'] ?>">
          <i class="icon <?= $tipoMenu['icon'] ?> me-2"></i>
          <?= $tipoMenu['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check" name="card-layout" id="card-with-border" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary rounded-2" for="card-with-border">
        <i class="icon ti ti-border-outer fs-7 me-2"></i>Border
      </label>

      <input type="radio" class="btn-check" name="card-layout" id="card-without-border" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary rounded-2" for="card-without-border">
        <i class="icon ti ti-border-none fs-7 me-2"></i>Shadow
      </label>
    </div>
  </div>
</div>
