<script>
  import Icono from "./Icono.svelte";

  /** @type {keyof typeof import('@fortawesome/fontawesome-free/metadata/icon-families.json')} */
  export let icono;
  export let descripcion = "";
  /** @type {import('../../tipos').BootstrapColor} */
  export let fondo = "info";
</script>

<div class="col">
  <div class="alert alert-{fondo} text-center">
    <Icono nombre={icono} class="fa-5x" />
    <h3 class="d-flex flex-wrap justify-content-center">
      <slot />
    </h3>
    {descripcion}
  </div>
</div>
