<div
  class="d-flex w-100"
  x-data='{
    empleados: JSON.parse(`<?= json_encode($empleados) ?>`),
    busqueda: "",
    empleado: JSON.parse(`<?= count($empleados) ? json_encode($empleados[0]) : '{}' ?>`),

    get empleadosFiltrados() {
      if (!this.busqueda) {
        return this.empleados;
      }

      return this.empleados.filter(empleado => {
        const nombreCompleto = empleado.nombreCompleto ? empleado.nombreCompleto.toLowerCase() : "";
        const cedula = empleado.cedula ? empleado.cedula.toString().toLowerCase() : "";
        const email = empleado.email ? empleado.email.toLowerCase() : "";

        const termino = this.busqueda.toLowerCase();

        return (
          nombreCompleto.includes(termino) ||
          cedula.includes(termino) ||
          email.includes(termino)
        );
      });
    },
  }'>
  <div class="left-part border-end w-20 flex-shrink-0 d-none d-lg-block">
    <div class="px-9 pt-4 pb-3">
      <button class="btn btn-primary fw-semibold py-8 w-100">Registrar empleado</button>
    </div>
    <!-- <ul class="list-group mh-n100 simplebar-scrollable-y" data-simplebar="init">
      <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
          <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
          <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
            <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
              <div class="simplebar-content" style="padding: 0px;">
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
                    <i class="ti ti-file-text fs-5"></i>Pending
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
              </div>
            </div>
          </div>
        </div>
        <div class="simplebar-placeholder" style="width: 192px; height: 370px;"></div>
      </div>
      <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
      </div>
      <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar" style="height: 134px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
      </div>
    </ul> -->
  </div>
  <div class="d-flex w-100">
    <div class="min-width-340">
      <div class="border-end user-chat-box h-100">
        <div class="px-4 pt-9 pb-6 d-none d-lg-block">
          <form class="position-relative">
            <input type="search" class="form-control search-chat py-2 ps-5" placeholder="Buscar Empleado" x-model="busqueda" />
            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
          </form>
        </div>
        <div class="app-chat">
          <ul class="chat-users mh-n100 simplebar-scrollable-y" data-simplebar="init">
            <div class="simplebar-wrapper" style="margin: 0px;">
              <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
              </div>
              <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                  <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
                    <div class="simplebar-content" style="padding: 0px;">
                      <template x-for="empleadoFiltrado in empleadosFiltrados">
                        <li>
                          <button
                            @click="empleado = empleadoFiltrado"
                            class="border-0 px-4 py-3 bg-hover-light-black d-flex align-items-center chat-user bg-light-subtle">
                            <span class="position-relative">
                              <img :src="empleadoFiltrado.url_imagen || './recursos/imagenes/profile/user-1.jpg'" alt="user-4" width="40" height="40" class="rounded-circle">
                            </span>
                            <div class="ms-6 d-inline-block w-75">
                              <h6
                                class="mb-1 fw-semibold chat-title"
                                x-text="empleadoFiltrado.nombreCompleto || `v-${empleadoFiltrado.cedula}`">
                              </h6>
                              <span
                                class="fs-2 text-body-color d-block"
                                x-text="empleadoFiltrado.email">
                              </span>
                            </div>
                          </button>
                        </li>
                      </template>
                    </div>
                  </div>
                </div>
              </div>
              <div class="simplebar-placeholder" style="width: 339px; height: 720px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
              <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
              <div class="simplebar-scrollbar" style="height: 69px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
            </div>
          </ul>
        </div>
      </div>
    </div>
    <div class="w-100">
      <div class="chat-container h-100 w-100">
        <div class="chat-box-inner-part h-100">
          <div class="chatting-box app-email-chatting-box">
            <div class="p-9 py-3 border-bottom chat-meta-user d-flex align-items-center justify-content-between">
              <h5 class="text-dark mb-0 fs-5">Detalles del empleado</h5>
            </div>
            <div class="position-relative overflow-hidden">
              <div class="position-relative">
                <div class="chat-box email-box mh-n100 p-9 tab-content" data-simplebar="init">
                  <div class="chat-list chat active-chat" data-user-id="1">
                    <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-3">
                        <img :src="empleado.url_imagen || './recursos/imagenes/profile/user-1.jpg'" alt="user4" width="72" height="72" class="rounded-circle">
                        <div>
                          <h6 class="fw-semibold fs-4 mb-0" x-text="empleado.nombreCompleto || `v-${empleado.cedula}`"></h6>
                          <p class="mb-0" x-text="empleado.roles && empleado.roles[0]"></p>
                          <p class="mb-0" x-text="empleado.negocio"></p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4 mb-7">
                        <p class="mb-1 fs-2">Número de teléfono</p>
                        <h6 class="fw-semibold mb-0" x-text="empleado.telefono"></h6>
                      </div>
                      <div class="col-8 mb-7">
                        <p class="mb-1 fs-2">Dirección de correo</p>
                        <h6 class="fw-semibold mb-0" x-text="empleado.email"></h6>
                      </div>
                      <div class="col-12 mb-9">
                        <p class="mb-1 fs-2">Dirección</p>
                        <h6 class="fw-semibold mb-0" x-text="empleado.direccion"></h6>
                      </div>
                      <div class="col-4 mb-7">
                        <p class="mb-1 fs-2">Ciudad</p>
                        <h6 class="fw-semibold mb-0" x-text="empleado.ciudad"></h6>
                      </div>
                      <div class="col-8 mb-7">
                        <p class="mb-1 fs-2">País</p>
                        <h6 class="fw-semibold mb-0" x-text="empleado.pais"></h6>
                      </div>
                    </div>
                    <div class="border-bottom pb-7 mb-4">
                      <p class="mb-2 fs-2">Notas</p>
                      <p class="mb-3 text-dark" x-text="empleado.notas"></p>
                    </div>
                    <form method="post" class="d-flex align-items-center gap-6">
                      <button
                        :formaction="`./empleados/${empleado.roles && empleado.roles[0] === 'Empleado superior' ? 'degradar' : 'promover'}/${empleado.id}`"
                        class="btn btn-primary"
                        x-text="empleado.roles && empleado.roles[0] === 'Empleado superior' ? 'Degradar a Vendedor' : 'Promover a Empleado Superior'">
                      </button>
                      <button
                        :formaction="`./empleados/${empleado.esta_despedido ? 'recontratar' : 'despedir'}/${empleado.id}`"
                        x-text="empleado.esta_despedido ? 'Recontratar' : 'Despedir'"
                        class="btn"
                        :class="{
                          'bg-danger-subtle text-danger': !empleado.esta_despedido,
                          'bg-success-subtle text-success': empleado.esta_despedido,
                        }"
                        fdprocessedid="83zpb">
                      </button>
                    </form>
                  </div>
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
      <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Contact </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="px-9 pt-4 pb-3">
      <button class="btn btn-primary fw-semibold py-8 w-100">Add New Contact</button>
    </div>
    <ul class="list-group h-n150 simplebar-scrollable-y" data-simplebar="init">
      <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
          <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
          <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
            <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
              <div class="simplebar-content" style="padding: 0px;">
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
              </div>
            </div>
          </div>
        </div>
        <div class="simplebar-placeholder" style="width: 330px; height: 370px;"></div>
      </div>
      <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
      </div>
      <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar" style="height: 80px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
      </div>
    </ul>
  </div>
</div>
