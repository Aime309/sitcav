<script>
  import {Link} from "svelte-routing"
  import AlternadorDelTema from "./AlternadorDelTema.svelte";
  import MenuInferior from "./MenuInferior.svelte";
  import Icono from "./Icono.svelte";
</script>

<div class="sticky-top d-flex justify-content-between p-3">
  <Link to="./panel">
    <img
      src="./imagenes/favicon.png"
      alt="Logo de SITCAV"
      height={55}
      title="SITCAV"
    />
  </Link>

  <div class="d-flex align-items-center">
    <AlternadorDelTema />
    <a href="./salir" class="btn btn-outline-danger d-flex align-items-center">
      <Icono nombre="right-from-bracket" class="fs-1" />
    </a>
  </div>
</div>

<MenuInferior />

<style>
  .sticky-top {
    backdrop-filter: blur(1em);
  }
</style>
