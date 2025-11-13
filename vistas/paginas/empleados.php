<?php

use SITCAV\Enums\Permiso;
use SITCAV\Enums\Rol;

$idModalRestablecerClave = uniqid();

?>

<div
  class="card overflow-hidden chat-application"
  x-data='{
    empleados: JSON.parse(`<?= $empleados->toJson() ?>`),
    busqueda: "",
    estadoEmpleado: "",
    roles: JSON.parse(`<?= Rol::comoJsonString() ?>`),
    rolSeleccionado: "",
    empleadoSeleccionado: {},

    iconos: {
      estado: {
        Despedidos: "bi bi-person-dash",
      },
    },

    get empleadosFiltrados() {
      return this.empleados.filter(empleado => {
        let debeMostrarse = true;

        debeMostrarse &&= (
          String(empleado.cedula).startsWith(this.busqueda)
          || String(empleado.email).toLowerCase().startsWith(this.busqueda.toLowerCase())
        );

        if (this.rolSeleccionado) {
          debeMostrarse &&= empleado.roles.includes(this.rolSeleccionado);
        }

        if (this.estadoEmpleado === "Despedidos") {
          debeMostrarse &&= empleado.esta_despedido;
        }

        return debeMostrarse;
      });
    },
  }'
  x-init="empleadoSeleccionado = empleados[0] || {}">
  <div class="d-flex align-items-center justify-content-between gap-3 m-3 d-lg-none">
    <button
      class="btn btn-primary d-flex"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#chat-sidebar">
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
  <div class="d-flex w-100">
    <div class="left-part border-end w-20 flex-shrink-0 d-none d-lg-block">
      <?php Flight::render('componentes/filtrosEmpleados') ?>
    </div>
    <div class="d-flex w-100">
      <div class="min-width-340">
        <div class="border-end user-chat-box h-100">
          <div class="px-4 pt-9 pb-6 d-none d-lg-block">
            <form class="position-relative my-3">
              <input
                type="search"
                class="form-control ps-5"
                placeholder="Buscar Empleado"
                x-model="busqueda" />
              <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
            </form>
          </div>
          <div class="app-chat">
            <ul class="chat-users list-unstyled">
              <template x-for="(empleadoIterado, indice) in empleadosFiltrados">
                <li @click="empleadoSeleccionado = {...empleadoIterado}">
                  <a
                    href="javascript:"
                    class="text-decoration-none p-3 bg-hover-light-black d-flex gap-3 align-items-center chat-user bg-light-subtle"
                    :id="`chat_user_${indice + 1}`"
                    :data-user-id="indice + 1">
                    <img
                      :src="empleadoIterado.url_imagen || './recursos/imagenes/profile/user-3.jpg'"
                      width="40"
                      height="40"
                      class="rounded-circle" />
                    <div>
                      <strong
                        class="chat-title"
                        :data-username="empleadoIterado.cedula"
                        x-text="empleadoIterado.cedula">
                      </strong>
                      <span x-text="empleadoIterado.email"></span>
                    </div>
                  </a>
                </li>
              </template>
            </ul>
          </div>
        </div>
      </div>
      <div class="w-100">
        <div class="chat-container h-100 w-100">
          <div class="chat-box-inner-part h-100">
            <div class="chatting-box app-email-chatting-box">
              <div class="p-3 border-bottom chat-meta-user d-flex align-items-center justify-content-between">
                <h5 class="text-dark mb-0 fs-5">Detalles del empleado</h5>
                <ul class="list-unstyled mb-0 d-flex align-items-center">
                  <li class="d-lg-none d-block">
                    <a
                      class="text-dark back-btn px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5"
                      href="javascript:void(0)">
                      <i class="bi bi-arrow-left"></i>
                    </a>
                  </li>
                  <li
                    class="position-relative <?= auth()->user()->cannot(Permiso::RESTABLECER_CLAVE_EMPLEADO->name) ? 'disabled opacity-25' : '' ?>"
                    style="<?= auth()->user()->cannot(Permiso::RESTABLECER_CLAVE_EMPLEADO->name) ? 'pointer-events: none' : '' ?>"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Restablecer contraseña">
                    <a
                      class="text-dark px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5"
                      href="#<?= $idModalRestablecerClave ?>"
                      data-bs-toggle="modal">
                      <i class="bi bi-unlock"></i>
                    </a>
                  </li>
                  <li
                    class="position-relative <?= auth()->user()->cannot([Permiso::PROMOVER_VENDEDOR->name, Permiso::DEGRADAR_EMPLEADO_SUPERIOR->name]) ? 'disabled opacity-25' : '' ?>"
                    style="<?= auth()->user()->cannot([Permiso::PROMOVER_VENDEDOR->name, Permiso::DEGRADAR_EMPLEADO_SUPERIOR->name]) ? 'pointer-events: none' : '' ?>"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="empleadoSeleccionado.id && empleadoSeleccionado.roles.includes('Empleado superior') ? 'Degradar a vendedor' : 'Promover a empleado superior'">
                    <a
                      class="d-block text-dark px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5"
                      :href="empleadoSeleccionado.id && `./empleados/${empleadoSeleccionado.roles.includes('Empleado superior') ? 'degradar' : 'promover'}/${empleadoSeleccionado.id}`">
                      <i
                        class="bi"
                        :class="{
                          'bi-person-down': empleadoSeleccionado.roles?.includes('Empleado superior'),
                          'bi-person-up': !empleadoSeleccionado.roles?.includes('Empleado superior'),
                        }">
                      </i>
                    </a>
                  </li>
                  <li
                    class="position-relative <?= auth()->user()->cannot([Permiso::DESPEDIR_EMPLEADO->name, Permiso::RECONTRATAR_EMPLEADO->name]) ? 'disabled opacity-25' : '' ?>"
                    style="<?= auth()->user()->cannot([Permiso::DESPEDIR_EMPLEADO->name, Permiso::RECONTRATAR_EMPLEADO->name]) ? 'pointer-events: none' : '' ?>"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    :title="empleadoSeleccionado.esta_despedido ? 'Recontratar' : 'Despedir'">
                    <a
                      class="text-dark px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5"
                      :href="empleadoSeleccionado.id && `./empleados/${empleadoSeleccionado.esta_despedido ? 'recontratar' : 'despedir'}/${empleadoSeleccionado.id}`">
                      <i
                        class="bi"
                        :class="{
                          'bi-person-fill-check': empleadoSeleccionado.esta_despedido,
                          'bi-person-fill-x': !empleadoSeleccionado.esta_despedido,
                        }">
                      </i>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="position-relative overflow-hidden">
                <div class="position-relative">
                  <div class="chat-box email-box p-3">
                    <template x-for="(empleadoIterado, indice) in empleadosFiltrados">
                      <div
                        class="chat-list chat"
                        :class="{ 'active-chat': !indice }"
                        :data-user-id="indice + 1">
                        <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between">
                          <div class="d-flex align-items-center gap-3">
                            <img
                              :src="empleadoIterado.url_imagen || './recursos/imagenes/profile/user-3.jpg'"
                              width="72"
                              height="72"
                              class="rounded-circle" />
                            <div>
                              <strong class="fw-semibold fs-4 mb-0" x-text="empleadoIterado.cedula"></strong>
                              <p class="mb-0" x-text="empleadoIterado.roles[0]"></p>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <!-- <div class="col-4 mb-7">
                            <p class="mb-1 fs-5">Número de teléfono</p>
                            <h6 class="fw-semibold mb-0" x-text="empleadoIterado.telefono"></h6>
                          </div>
                          <div class="col-8 mb-7">
                            <p class="mb-1 fs-5">Dirección de correo</p>
                            <h6 class="fw-semibold mb-0" x-text="empleadoIterado.email"></h6>
                          </div> -->
                          <!-- <div class="col-12 mb-9">
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
                          </div> -->
                        </div>
                        <!-- <div class="border-bottom pb-7 mb-4">
                          <p class="mb-2 fs-2">Notes</p>
                          <p class="mb-3 text-dark">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum
                            hendrerit lobortis. Nullam ut lacus eros. Sed at luctus urna, eu fermentum
                            diam.
                            In et tristique mauris.
                          </p>
                          <p class="mb-0 text-dark">Ut id ornare metus, sed auctor enim. Pellentesque
                            nisi magna, laoreet a augue eget, tempor volutpat diam.</p>
                        </div> -->
                        <div class="d-flex align-items-center gap-6 btn-group py-5">
                          <a
                            href="#<?= $idModalRestablecerClave ?>"
                            data-bs-toggle="modal"
                            class="btn btn-primary <?= auth()->user()->cannot(Permiso::RESTABLECER_CLAVE_EMPLEADO->name) ? 'disabled opacity-25' : '' ?>"
                            style="<?= auth()->user()->cannot(Permiso::RESTABLECER_CLAVE_EMPLEADO->name) ? 'pointer-events: none' : '' ?>">
                            <i class="bi bi-unlock"></i>
                            Restablecer contraseña
                          </a>
                          <a
                            :href="`./empleados/${empleadoIterado.roles.includes('Empleado superior') ? 'degradar' : 'promover'}/${empleadoIterado.id}`"
                            class="btn <?= auth()->user()->cannot([Permiso::PROMOVER_VENDEDOR->name, Permiso::DEGRADAR_EMPLEADO_SUPERIOR->name]) ? 'disabled opacity-25' : '' ?>"
                            :class="{
                              'btn-warning': empleadoIterado.roles.includes('Empleado superior'),
                              'btn-success': !empleadoIterado.roles.includes('Empleado superior'),
                            }"
                            style="<?= auth()->user()->cannot([Permiso::PROMOVER_VENDEDOR->name, Permiso::DEGRADAR_EMPLEADO_SUPERIOR->name]) ? 'pointer-events: none' : '' ?>">
                            <i
                              class="bi"
                              :class="{
                                'bi-person-down': empleadoIterado.roles.includes('Empleado superior'),
                                'bi-person-up': !empleadoIterado.roles.includes('Empleado superior'),
                              }">
                            </i>
                            <span x-text="empleadoIterado.roles.includes('Empleado superior') ? 'Degradar a vendedor' : 'Promover a empleado superior'"></span>
                          </a>
                          <a
                            :href="`./empleados/${empleadoIterado.esta_despedido ? 'recontratar' : 'despedir'}/${empleadoIterado.id}`"
                            class="btn <?= auth()->user()->cannot([Permiso::DESPEDIR_EMPLEADO->name, Permiso::RECONTRATAR_EMPLEADO->name]) ? 'disabled opacity-25' : '' ?>"
                            :class="{
                              'btn-success': empleadoIterado.esta_despedido,
                              'btn-danger': !empleadoIterado.esta_despedido,
                            }"
                            style="<?= auth()->user()->cannot([Permiso::DESPEDIR_EMPLEADO->name, Permiso::RECONTRATAR_EMPLEADO->name]) ? 'pointer-events: none' : '' ?>">
                            <i
                              class="bi"
                              :class="{
                                'bi-person-fill-check': empleadoIterado.esta_despedido,
                                'bi-person-fill-x': !empleadoIterado.esta_despedido,
                              }">
                            </i>
                            <span x-text="empleadoIterado.esta_despedido ? 'Recontratar' : 'Despedir'"></span>
                          </a>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="offcanvas offcanvas-start user-chat-box" tabindex="-1" id="chat-sidebar" aria-labelledby="offcanvasExampleLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Empleados</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <?php Flight::render('componentes/filtrosEmpleados') ?>
    </div>
  </div>

  <div class="modal fade" id="<?= $idModalRestablecerClave ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <div>
            <h4 class="modal-title">Restablecer clave</h4>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body w-100 border position-relative overflow-hidden">
          <form :action="`./empleados/restablecer-clave/${empleadoSeleccionado.id}`" method="post" class="d-grid gap-3">
            <?php Flight::render('componentes/input-clave', [
              'required' => true,
              'label' => 'Nueva contraseña',
              'name' => 'nueva_clave',
            ]) ?>
            <button class="btn btn-primary">Restablecer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script defer src="./recursos/js/apps/chat.js"></script>
