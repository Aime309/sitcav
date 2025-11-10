<?php

$idNav = uniqid();
$mensajes = [];
$notificationes = [];

?>

<a
  class="navbar-toggler p-0 border-0 nav-icon-hover"
  href="#<?= $idNav ?>"
  data-bs-toggle="collapse">
  <span class="navbar-toggler-icon"></span>
</a>
<div class="collapse navbar-collapse justify-content-end" id="<?= $idNav ?>">
  <div class="d-flex align-items-center justify-content-between">
    <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
      <!-- <li class="nav-item nav-icon-hover dropdown">
        <a
          href="#<?= $idEnlacesMovilOffcanvas ?>"
          class="nav-link d-flex d-lg-none align-items-center justify-content-center"
          type="button"
          data-bs-toggle="offcanvas"
          aria-controls="offcanvasWithBothOptions">
          <i class="bi bi-list-nested"></i>
        </a>
      </li> -->
      <!-- <li class="nav-item nav-icon-hover">
        <a
          class="nav-link"
          href="#<?= $idModalBusqueda ?>"
          data-bs-toggle="modal">
          <i class="bi bi-search"></i>
        </a>
      </li> -->
      <!-- Productos en carrito -->
      <li class="nav-item nav-icon-hover">
        <a
          class="nav-link position-relative"
          href="./vender">
          <i class="bi bi-basket3-fill position-relative">
            <span
              x-text="Object.values(productosEnCarrito).reduce((a, b) => a + b, 0)"
              x-show="Object.values(productosEnCarrito).reduce((a, b) => a + b, 0) > 0"
              class="badge text-bg-primary position-absolute top-75 start-50">
            </span>
          </i>
        </a>
      </li>
      <li class="nav-item nav-icon-hover">
        <button class="nav-link" @click="tema = tema === 'light' ? 'dark' : 'light'">
          <i
            class="bi"
            :class="{
              'bi-sun': tema === 'light',
              'bi-moon': tema === 'dark',
            }">
          </i>
        </button>
      </li>
      <li
        class="nav-item nav-icon-hover dropdown <?= !$mensajes ? 'disabled opacity-25' : '' ?>"
        style="<?= !$mensajes ? 'pointer-events: none' : '' ?>">
        <button class="nav-link">
          <i class="bi bi-envelope-open position-relative">
            <?php if ($mensajes): ?>
              <span class="badge text-bg-primary position-absolute top-75 start-50">
                <?= count($mensajes) ?>
              </span>
            <?php endif ?>
          </i>
        </button>
        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up py-0">
          <div class="d-flex align-items-center justify-content-between p-3">
            <h2 class="m-0 fs-5">Bandeja de entrada</h2>
            <span class="badge text-bg-warning rounded-4 px-3 py-1 lh-sm">3 new</span>
          </div>
          <div class="message-body overflow-x-hidden overflow-y-auto">
            <?php foreach ($mensajes as $mensaje): ?>
              <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                <span class="me-3 position-relative">
                  <img src="./recursos/imagenes/profile/user-6.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                  <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                  </span>
                </span>
                <div class="w-75 v-middle">
                  <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-1">Michell Flintoff</h6>
                    <span class="fs-2 d-block">just now</span>
                  </div>
                  <span class="d-block w-100 text-truncate">You: Yesterdy was great...</span>
                </div>
              </a>
            <?php endforeach ?>
          </div>
          <a href="./mensajes" class="btn btn-outline-primary w-100 rounded-0">
            Ver todos los mensajes
          </a>
        </div>
      </li>
      <li
        class="nav-item nav-icon-hover dropdown <?= !$notificationes ? 'disabled opacity-25' : '' ?>"
        style="<?= !$notificationes ? 'pointer-events: none' : '' ?>">
        <button class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
          <i class="bi bi-bell position-relative">
            <?php if ($notificationes): ?>
              <span class="badge text-bg-primary position-absolute top-75 start-50">
                <?= count($notificationes) ?>
              </span>
            <?php endif ?>
          </i>
        </button>
        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up">
          <div class="d-flex align-items-center justify-content-between py-3 px-7">
            <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
            <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
          </div>
          <div class="message-body" data-simplebar>
            <?php foreach ($notificationes as $notificacion): ?>
              <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                  <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                </span>
                <div class="w-75 d-inline-block v-middle">
                  <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                    <span class="d-block fs-2">9:30 AM</span>
                  </div>
                  <span class="d-block text-truncate text-truncate">Just see the my new admin!</span>
                </div>
              </a>
            <?php endforeach ?>
          </div>
          <div class="py-6 px-7 mb-1">
            <button class="btn btn-outline-primary w-100">See All Notifications</button>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <?php Flight::render('componentes/dropdown-avatar') ?>
      </li>
    </ul>
  </div>
</div>
