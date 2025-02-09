<script>
  import { Offcanvas } from "@sveltestrap/sveltestrap";
  import ListadoDeModulos from "./ListadoDeModulos.svelte";

  let abierto = false;

  function alternarMostrarOcultarMenu() {
    abierto = !abierto;
  }
</script>

<nav class="navbar navbar-expand-xxl fixed-bottom">
  <div class="container-fluid">
    <button
      on:click={alternarMostrarOcultarMenu}
      class="navbar-toggler p-3 rounded-circle mx-auto border-5 border-primary bg-white"
      type="button"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<Offcanvas
  isOpen={abierto}
  toggle={alternarMostrarOcultarMenu}
  scroll
  placement="bottom"
  style="height: 90%"
>
  <ListadoDeModulos />
</Offcanvas>

