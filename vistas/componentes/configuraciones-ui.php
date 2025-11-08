<?php

$colores = [
  [
    'id' => 'Blue_Theme',
    'class' => 'skin-1',
    'title' => 'BLUE_THEME'
  ],
  [
    'id' => 'Aqua_Theme',
    'class' => 'skin-2',
    'title' => 'AQUA_THEME'
  ],
  [
    'id' => 'Purple_Theme',
    'class' => 'skin-3',
    'title' => 'PURPLE_THEME'
  ],
  [
    'id' => 'Green_Theme',
    'class' => 'skin-4',
    'title' => 'GREEN_THEME'
  ],
  [
    'id' => 'Cyan_Theme',
    'class' => 'skin-5',
    'title' => 'CYAN_THEME'
  ],
  [
    'id' => 'Orange_Theme',
    'class' => 'skin-6',
    'title' => 'ORANGE_THEME'
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
          x-model="direccion"
        />
        <label class="btn btn-outline-primary" for="<?= $direccion['id'] ?>">
          <i class="icon <?= $direccion['icon'] ?> me-2"></i>
          <?= $direccion['title'] ?>
        </label>
      <?php endforeach ?>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Tema de Colores</h6>

    <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
      <?php foreach ($colores as $color): ?>
        <input
          type="radio"
          class="btn-check"
          name="color-theme-layout"
          id="<?= $color['id'] ?>"
          autocomplete="off" />
        <label
          class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
          @click="tema_colores = '<?= $color['id'] ?>'"
          for="<?= $color['id'] ?>"
          data-bs-toggle="tooltip"
          data-bs-placement="top"
          data-bs-title="<?= $color['title'] ?>">
          <div class="color-box rounded-circle d-flex align-items-center justify-content-center <?= $color['class'] ?>">
            <i class="bi bi-check text-white d-flex icon"></i>
          </div>
        </label>
      <?php endforeach ?>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <div>
        <input type="radio" class="btn-check" name="page-layout" id="vertical-layout" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary rounded-2" for="vertical-layout">
          <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
        </label>
      </div>
      <div>
        <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary rounded-2" for="horizontal-layout">
          <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
        </label>
      </div>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary rounded-2" for="boxed-layout">
        <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed
      </label>

      <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary rounded-2" for="full-layout">
        <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full
      </label>
    </div>

    <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
    <div class="d-flex flex-row gap-3 flex-wrap customizer-box" role="group">
      <a href="javascript:void(0)" class="fullsidebar">
        <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary rounded-2" for="full-sidebar">
          <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full
        </label>
      </a>
      <div>
        <input type="radio" class="btn-check" name="sidebar-type" id="mini-sidebar" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary rounded-2" for="mini-sidebar">
          <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse
        </label>
      </div>
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
