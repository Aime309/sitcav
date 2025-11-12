<a href="./empleados/registrar" class="btn btn-primary w-100 rounded-0 mb-5">
  Contratar empleado
</a>
<ul class="list-group">
  <li class="list-group-item p-0">
    <button
      @click="estadoEmpleado = ''"
      class="btn rounded-0 d-flex align-items-center gap-3 list-group-item-action">
      <i class="bi bi-envelope-open"></i>
      Todos los empleados
    </button>
  </li>
  <template x-for="estadoIterado in ['Despedidos']">
    <li class="list-group-item p-0">
      <button
        @click="estadoEmpleado = estadoIterado"
        class="btn rounded-0 d-flex align-items-center gap-3 list-group-item-action">
        <i :class="iconos.estado[estadoIterado] || 'bi bi-envelope-open'"></i>
        <span x-text="estadoIterado"></span>
      </button>
    </li>
  </template>
  <li class="px-3 fs-2">Roles</li>
  <template x-for="rolIterado in roles">
    <li class="list-group-item p-0">
      <button
        class="btn rounded-0 d-flex align-items-center gap-3 list-group-item-action"
        @click="rolSeleccionado = rolIterado">
        <i class="bi bi-bookmark"></i>
        <span x-text="rolIterado"></span>
      </button>
    </li>
  </template>
</ul>
