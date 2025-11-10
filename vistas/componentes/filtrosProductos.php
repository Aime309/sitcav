<ul class="list-group border-bottom" style="width: max-content !important">
  <h3 class="fs-5 p-3 m-0">Filtrar por categoría</h3>
  <li
    class="list-group-item p-0"
    :class="{ active: !categoriaSeleccionada }">
    <button class="list-group-item-action btn" @click="categoriaSeleccionada = ''">
      <i class="bi bi-circle"></i>
      Todas
    </button>
  </li>
  <template x-for="categoriaIterada in categorias">
    <li
      class="list-group-item p-0"
      :class="{ active: categoriaIterada.nombre === categoriaSeleccionada }">
      <button class="list-group-item-action btn" @click="categoriaSeleccionada = categoriaIterada.id">
        <i :class="iconos.categorias[categoriaIterada.nombre] || 'bi bi-tag-fill'"></i>
        <span x-text="categoriaIterada.nombre"></span>
      </button>
    </li>
  </template>
</ul>
<ul class="list-group border-bottom disabled opacity-25" style="pointer-events: none">
  <h3 class="fs-5 p-3 m-0">Ordenar por</h3>
  <template x-for="tipoOrden of ['Los más nuevos', 'Los más caros', 'Los más baratos', 'Descontinuados']">
    <li
      class="list-group-item p-0"
      :class="{ active: tipoOrden === ordenarPor }">
      <button class="list-group-item-action btn" @click="ordenarPor = tipoOrden">
        <i :class="iconos.tipoOrden[tipoOrden]"></i>
        <span x-text="tipoOrden"></span>
      </button>
    </li>
  </template>
</ul>
<h3 class="fs-5 p-3 m-0">Por precio</h3>
<div class="px-3 pb-3 border-bottom">
  <template
    x-for="precioIterado in [
      {
        id: 1,
        etiqueta: 'Cualquier precio',
        valor: '',
      },
      {
        id: 2,
        etiqueta: '$0-50',
        valor: '0-50',
      },
      {
        id: 3,
        etiqueta: '$50-100',
        valor: '50-100',
      },
      {
        id: 4,
        etiqueta: '$100-200',
        valor: '100-200',
      },
      {
        id: 5,
        etiqueta: 'Más de $200',
        valor: '>200',
      }
    ]">
    <div class="form-check">
      <input
        class="form-check-input"
        type="radio"
        name="exampleRadios"
        :id="`precio-${precioIterado.id}`"
        :value="precioIterado.valor"
        x-model="precios" />
      <label
        class="form-check-label"
        :for="`precio-${precioIterado.id}`"
        x-text="precioIterado.etiqueta">
      </label>
    </div>
  </template>
</div>
<button
  class="btn btn-primary w-100"
  @click="
    categoriaSeleccionada = '';
    ordenarPor = '';
    precios = '';
  ">
  Reiniciar filtros
</button>
