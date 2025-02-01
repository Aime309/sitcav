<script>
  import { Link } from "svelte-routing";
  import Icono from "./Icono.svelte";

  /** @type {string} */
  export let url;

  /** @type {import('../../tipos').BootstrapColor} */
  export let fondo = "primary"

  /** @type {keyof typeof import('@fortawesome/fontawesome-free/metadata/icon-families.json')} */
  export let icono;
</script>

<div class="col">
  <div class="ratio ratio-1x1">
    <Link
      to={url}
      class="border-5 rounded-circle card card-body justify-content-center btn btn-{fondo}"
    >
      <Icono
        nombre={icono}
        class="card-img-top"
        style="font-size: 5em"
      />
      <h4 class="h5 m-0">
        <slot />
      </h4>
    </Link>
  </div>
</div>
