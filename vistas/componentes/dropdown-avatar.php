<?php

$posicion ??= 'dropup';
$tooltip ??= 'right';

$enlacesPerfil = [
  [
    'icon' => 'bi bi-person-circle',
    'title' => 'Mi perfil',
    'subtitle' => 'Configuraciones de la cuenta',
    'href' => './perfil',
  ],
  [
    'icon' => 'bi bi-envelope',
    'title' => 'Mis bandeja de entrada',
    'subtitle' => 'Mensajes y correos',
    'href' => './notificaciones',
  ],
  [
    'icon' => 'bi bi-check2-square',
    'title' => 'Mis tareas',
    'subtitle' => 'Quehaceres y tareas diarias',
    'href' => './tareas',
  ],
];

?>

<div class="dropdown">
  <button
    class="border-0 btn btn-outline-primary w-100"
    data-bs-toggle="dropdown">
    <img
      src="./recursos/imagenes/profile/user-1.jpg"
      class="rounded-circle img-fluid"
      width="35"
      height="35" />
  </button>
  <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up shadow-lg pb-0">
    <div class="profile-dropdown position-relative overflow-y-auto overflow-x-hidden">
      <h2 class="px-3">Perfil de usuario</h2>
      <div class="d-flex gap-3 align-items-center border-bottom pb-3 px-3">
        <img
          src="./recursos/imagenes/profile/user-1.jpg"
          class="rounded-circle object-fit-contain"
          width="80"
          height="80" />
        <div class="d-grid overflow-x-auto">
          <strong>
            <?= (
              auth()->user()?->nombreCompleto
              ?: (auth()->user()?->cedula && sprintf('v-%s', auth()->user()?->cedula))
              ?: ''
            ) ?>
          </strong>
          <span><?= auth()->user()?->roles()[0] ?></span>
          <span class="d-flex align-items-center gap-3">
            <i class="bi bi-envelope"></i>
            <?= auth()->user()?->email ?>
          </span>
        </div>
      </div>
      <div class="message-body">
        <?php foreach ($enlacesPerfil as $enlace): ?>
          <a href="<?= $enlace['href'] ?>" class="d-flex align-items-stretch gap-3 text-decoration-none link-primary">
            <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary p-3">
              <i class="<?= $enlace['icon'] ?>"></i>
            </span>
            <div class="w-75 d-flex flex-column py-3">
              <strong><?= $enlace['title'] ?></strong>
              <span class="text-muted"><?= $enlace['subtitle'] ?></span>
            </div>
          </a>
        <?php endforeach ?>
      </div>
      <a href="./salir" class="btn btn-primary w-100 rounded-0">Cerrar sesión</a>
    </div>
  </div>
</div>
</li>
