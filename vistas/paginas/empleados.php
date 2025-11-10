<?php

use SITCAV\Enums\Rol;

$idModalFiltros = uniqid();

?>

<article
  class="card overflow-hidden chat-application"
  x-data='{
    empleados: JSON.parse(`<?= json_encode($empleados) ?>`),
    roles: JSON.parse(`<?= Rol::comoJsonString() ?>`),
    rolSeleccionado: "",
    estadoEmpleado: "",
    busqueda: "",
    empleadoSeleccionado: {},

    iconos: {
      rol: {
        "Encargado": "bi bi-person-badge-fill",
        "Vendedor": "bi bi-person-lines-fill",
        "Empleado superior": "bi bi-person-check-fill",
      },
      estadoEmpleado: {
        "Despedidos": "bi bi-person-x-fill",
      },
    },

    get empleadosFiltrados() {
      return this.empleados.filter(empleado => {
        let debeMostrarse = true;

        debeMostrarse &&= (
          String(empleado.email).toLowerCase().startsWith(this.busqueda.toLowerCase())
          || String(empleado.cedula).startsWith(this.busqueda)
        );

        return debeMostrarse;
      });
    },
  }'>
  <div class="d-flex align-items-center justify-content-between mb-5 mt-3 mx-3 d-lg-none">
    <button
      class="btn"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#<?= $idModalFiltros ?>">
      <i class="bi bi-list"></i>
    </button>
    <form class="position-relative">
      <input
        type="search"
        class="form-control ps-5"
        placeholder="Buscar Empleado"
        x-model="busqueda" />
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
    </form>
  </div>
  <div class="d-flex">
    <div class="border-end d-none d-lg-block w-50">
      <a href="./empleados/registrar" class="btn btn-primary w-100 rounded-0">
        Añadir nuevo empleado
      </a>
      <ul class="list-group mt-3">
        <li
          class="list-group-item p-0"
          :class="{ active: !estadoEmpleado }">
          <button class="list-group-item-action btn" @click="estadoEmpleado = ''">
            <i class="bi bi-envelope-open"></i>
            Todos los empleados
          </button>
        </li>
        <template x-for="estadoEmpleadoIterado in ['Despedidos']">
          <li
            class="list-group-item p-0"
            :class="{ active: estadoEmpleado === estadoEmpleadoIterado }">
            <button class="list-group-item-action btn" @click="estadoEmpleado = estadoEmpleadoIterado">
              <i :class="iconos.estadoEmpleado[estadoEmpleadoIterado] || 'bi bi-envelope-open'"></i>
              <span x-text="estadoEmpleadoIterado"></span>
            </button>
          </li>
        </template>
      </ul>
      <ul class="list-group">
        <h3 class="fs-5 p-3 m-0">Roles</h3>
        <template x-for="rol in roles">
          <li
            class="list-group-item p-0"
            :class="{ active: rol === rolSeleccionado }">
            <button class="list-group-item-action btn" @click="rolSeleccionado = rol">
              <i :class="iconos.rol[rol] || 'bi bi-person-fill'"></i>
              <span x-text="rol"></span>
            </button>
          </li>
        </template>
      </ul>
    </div>
    <div class="d-flex">
      <div class="border-end w-100">
        <div class="d-none d-lg-block mb-5 mt-3 mx-3">
          <form class="position-relative">
            <input
              type="search"
              class="form-control ps-5"
              placeholder="Buscar Empleado"
              x-model="busqueda" />
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
          </form>
        </div>
        <ul class="list-group px-3">
          <template x-for="empleadoIterado in empleadosFiltrados">
            <li
              class="list-group-item p-0"
              :class="{ active: empleadoIterado.id === empleadoSeleccionado.id }">
              <button
                @click="empleadoSeleccionado = {...empleadoIterado}"
                class="list-group-item-action btn px-3 d-flex align-items-center gap-3">
                <img
                  :src="empleadoIterado.url_imagen || './recursos/imagenes/profile/user-3.jpg'"
                  width="40"
                  height="40"
                  class="rounded-circle" />
                <div class="d-inline-block w-75">
                  <strong x-text="empleadoIterado.cedula && `v-${empleadoIterado.cedula}`"></strong>
                  <span x-text="empleadoIterado.email"></span>
                </div>
              </button>
            </li>
          </template>
        </ul>
      </div>
      <div class="w-100">
        <div class="chatting-box app-email-chatting-box">
          <div class="border-bottom d-flex align-items-center justify-content-between">
            <h3 class="m-3 fs-5">Detalles del empleado</h3>
            <ul class="list-unstyled mb-0 d-flex align-items-center">
              <li class="d-lg-none d-block">
                <button
                  class="btn nav-icon-hover position-relative z-index-5">
                  <i class="bi bi-arrow-left"></i>
                </button>
              </li>
              <!-- <li class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                <a class="text-dark px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5" href="javascript:void(0)">
                  <i class="ti ti-trash"></i>
                </a>
              </li> -->
            </ul>
          </div>
          <div class="position-relative overflow-hidden">
            <div class="position-relative">
              <div class="chat-box email-box mh-n100 p-9" data-simplebar="init">
                <div class="chat-list chat active-chat" data-user-id="1">
                  <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                      <img src="../assets/images/profile/user-3.jpg" alt="user4" width="72" height="72" class="rounded-circle">
                      <div>
                        <h6 class="fw-semibold fs-4 mb-0">Dr. Bonnie Barstow </h6>
                        <p class="mb-0">Sales Manager</p>
                        <p class="mb-0">Digital Arc Pvt. Ltd.</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-4 mb-7">
                      <p class="mb-1 fs-2">Phone number</p>
                      <h6 class="fw-semibold mb-0">+1 (203) 3458</h6>
                    </div>
                    <div class="col-8 mb-7">
                      <p class="mb-1 fs-2">Email address</p>
                      <h6 class="fw-semibold mb-0">alexandra@modernize.com</h6>
                    </div>
                    <div class="col-12 mb-9">
                      <p class="mb-1 fs-2">Address</p>
                      <h6 class="fw-semibold mb-0">312, Imperical Arc, New western corner</h6>
                    </div>
                    <div class="col-4 mb-7">
                      <p class="mb-1 fs-2">City</p>
                      <h6 class="fw-semibold mb-0">New York</h6>
                    </div>
                    <div class="col-8 mb-7">
                      <p class="mb-1 fs-2">Country</p>
                      <h6 class="fw-semibold mb-0">United Stats</h6>
                    </div>
                  </div>
                  <div class="border-bottom pb-7 mb-4">
                    <p class="mb-2 fs-2">Notes</p>
                    <p class="mb-3 text-dark">
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum
                      hendrerit lobortis. Nullam ut lacus eros. Sed at luctus urna, eu fermentum
                      diam.
                      In et tristique mauris.
                    </p>
                    <p class="mb-0 text-dark">Ut id ornare metus, sed auctor enim. Pellentesque
                      nisi magna, laoreet a augue eget, tempor volutpat diam.</p>
                  </div>
                  <div class="d-flex align-items-center gap-6">
                    <button class="btn btn-primary" fdprocessedid="pk6kl8">Edit</button>
                    <button class="btn bg-danger-subtle text-danger" fdprocessedid="83zpb">Delete</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="offcanvas offcanvas-start user-chat-box" tabindex="-1" id="<?= $idModalFiltros ?>" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Contact </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="px-9 pt-4 pb-3">
        <button class="btn btn-primary fw-semibold py-8 w-100">Add New Contact</button>
      </div>
      <ul class="list-group h-n150" data-simplebar>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-inbox fs-5"></i>All Contacts
          </a>
        </li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-star"></i>Starred
          </a>
        </li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-file-text fs-5"></i>Pening Approval
          </a>
        </li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-alert-circle"></i>Blocked
          </a>
        </li>
        <li class="border-bottom my-3"></li>
        <li class="fw-semibold text-dark text-uppercase mx-9 my-2 px-3 fs-2">CATEGORIES</li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-bookmark fs-5 text-primary"></i>Engineers
          </a>
        </li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-bookmark fs-5 text-warning"></i>Support Staff
          </a>
        </li>
        <li class="list-group-item border-0 p-0 mx-9">
          <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
            <i class="ti ti-bookmark fs-5 text-success"></i>Sales Team
          </a>
        </li>
      </ul>
    </div>
  </div>
</article>
