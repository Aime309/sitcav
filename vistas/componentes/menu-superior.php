<?php

use SITCAV\Modelos\Cotizacion;

$notificaciones = [
  ["mensaje" => "Item 1", "enlace" => "javascript:void(0)"],
  ["mensaje" => "Item 2", "enlace" => "javascript:void(0)"],
];

$token = auth()->oauthToken();

if ($token) {
  try {
    $usuario = auth()->client('google')->getResourceOwner($token)->toArray();
  } catch (Throwable) {
  }
}

$avatar = $usuario['picture'] ?? './recursos/imagenes/profile/user-1.jpg';
$ultimaCotizacion = Cotizacion::query()->latest()->get()[0] ?? new Cotizacion;

?>

<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav gap-3 align-items-center justify-content-between flex-grow-1">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
          <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
          <div class="notification bg-primary rounded-circle"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
          <div class="message-body">
            <?php foreach ($notificaciones as $notificacion): ?>
              <a href="<?= $notificacion['enlace'] ?>" class="dropdown-item">
                <?= $notificacion['mensaje'] ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <form action="./cotizaciones" method="post" class="d-flex align-items-center gap-2 text-nowrap">
          Tasa según
          <a href="https://www.bcv.org.ve/">bcv.org.ve</a>
          <output x-data="tasas" class="badge" :class="(tasaDePagina === 'Error de conexión' || tasaDePagina === 'Cargando') ? 'text-bg-danger' : 'text-bg-info'" x-text="tasaDePagina"></output>
          <div class="input-group">
            <div class="form-floating">
              <input
                type="number"
                step=".01"
                name="nueva_tasa"
                required
                placeholder="Tasa BCV"
                value="<?= $ultimaCotizacion->tasa_bcv ?>"
                class="form-control" />
              <label>Tasa BCV</label>
            </div>
            <button class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <li class="nav-item dropdown">
          <a
            class="nav-link"
            href="javascript:void(0)"
            id="drop2"
            data-bs-toggle="dropdown"
            aria-expanded="false">
            <img
              src="<?= $avatar ?>"
              width="35"
              height="35"
              class="rounded-circle" />
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
            <div class="message-body">
              <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user fs-6"></i>
                <p class="mb-0 fs-3">My Profile</p>
              </a>
              <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-mail fs-6"></i>
                <p class="mb-0 fs-3">My Account</p>
              </a>
              <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-list-check fs-6"></i>
                <p class="mb-0 fs-3">My Task</p>
              </a>
              <a href="./salir" class="btn btn-outline-primary mx-3 mt-2 d-block">Cerrar sesión</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
