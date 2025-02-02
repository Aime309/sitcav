<script>
  import tema from "../globales/tema";
  import Icono from "./Icono.svelte";
</script>

<div class="dropdown">
  <button
    class="nav-link text-bg-{$tema} bg-transparent"
    data-bs-toggle="dropdown"
  >
    {#if $tema === "dark"}
      <Icono nombre="moon" tamañoFijo tamaño="2x" />
    {:else if $tema === "light"}
      <Icono nombre="sun" tamañoFijo tamaño="2x" />
    {/if}
  </button>
  <div class="dropdown-menu dropdown-menu-end p-0">
    <li class="dropdown-item p-0">
      <button
        class="btn btn-outline-primary w-100 rounded-0 d-flex justify-content-between align-items-center gap-2"
        class:active={$tema === "light"}
        on:click={tema.activarTemaClaro}
      >
        <Icono nombre="sun" tamañoFijo />
        Claro
      </button>
    </li>
    <li class="dropdown-item p-0">
      <button
        class="btn btn-outline-primary w-100 rounded-0 d-flex justify-content-between align-items-center gap-2"
        class:active={$tema === "dark"}
        on:click={tema.activarTemaOscuro}
      >
        <Icono nombre="moon" tamañoFijo />
        Oscuro
      </button>
    </li>
  </div>
</div>
