<?php

$colores = [
  [
    'id' => 'Blue_Theme',
    'value' => 'Blue_Theme',
    'class' => 'skin-1',
    'title' => 'Tema azul',
  ],
  [
    'id' => 'Aqua_Theme',
    'value' => 'Aqua_Theme',
    'class' => 'skin-2',
    'title' => 'Tema aqua',
  ],
  [
    'id' => 'Purple_Theme',
    'value' => 'Purple_Theme',
    'class' => 'skin-3',
    'title' => 'Tema púrpura',
  ],
  [
    'id' => 'Green_Theme',
    'value' => 'Green_Theme',
    'class' => 'skin-4',
    'title' => 'Tema verde',
  ],
  [
    'id' => 'Cyan_Theme',
    'value' => 'Cyan_Theme',
    'class' => 'skin-5',
    'title' => 'Tema cyan',
  ],
  [
    'id' => 'Orange_Theme',
    'value' => 'Orange_Theme',
    'class' => 'skin-6',
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
    'title' => 'Con borde',
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

?>

<button
  class="btn rounded-circle customizer-btn"
  :class="`btn-${temaInverso}`"
  type="button"
  data-bs-toggle="offcanvas"
  data-bs-target="#<?= $id ?>"
  style="aspect-ratio: 1/1">
  <i class="bi bi-gear"></i>
</button>

<div class="offcanvas customizer offcanvas-end bg-primary-subtle" id="<?= $id ?>">
  <div class="offcanvas-header">
    <h2 class="offcanvas-title">Configuraciones</h2>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-grid gap-3">
    <h3 class="m-0">Tema</h3>

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

    <h3 class="m-0">Dirección</h3>

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

    <h3 class="m-0">Tema de Colores</h3>

    <div class="d-flex flex-wrap gap-3 customizer-box color-pallete">
      <?php foreach ($colores as $color): ?>
        <input
          class="btn-check"
          id="<?= $color['id'] ?>"
          name="color-theme-layout"
          type="radio"
          value="<?= $color['value'] ?>"
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

    <!-- <h3 class="m-0">Posición de la navegación</h3>

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
    </div> -->

    <h3 class="m-0">Anchura</h3>

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

    <h3 class="m-0">Tipo de menú de navegación</h3>

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

    <h3 class="m-0">Tipo de tarjetas</h3>

    <div class="d-flex gap-3 customizer-box">
      <?php foreach ($tiposTarjeta as $tipoTarjeta): ?>
        <input
          class="btn-check"
          id="<?= $tipoTarjeta['id'] ?>"
          name="card-type"
          type="radio"
          value="<?= $tipoTarjeta['value'] ?>"
          x-model="tipo_tarjeta" />
        <label
          class="btn btn-outline-primary"
          for="<?= $tipoTarjeta['id'] ?>">
          <i class="icon <?= $tipoTarjeta['icon'] ?> me-2"></i>
          <?= $tipoTarjeta['title'] ?>
        </label>
      <?php endforeach ?>
    </div>
  </div>
</div>
