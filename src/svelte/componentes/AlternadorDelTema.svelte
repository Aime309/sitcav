<script>
  import tema from "../globales/tema";
</script>

<div class="dropdown">
  <button
    class="btn btn-link text-decoration-none d-flex align-items-center fs-1"
    class:text-light={$tema === "dark"}
    class:text-dark={$tema === "light"}
    data-bs-toggle="dropdown"
  >
    {#if $tema === "dark"}
      <i class="fa-solid fa-moon"></i>
    {:else if $tema === "light"}
      <i class="fa-solid fa-sun"></i>
    {/if}
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <button
        class="dropdown-item d-flex align-items-center"
        class:active={$tema === 'light'}
        on:click={tema.activarTemaClaro}
      >
        <i class="fa-solid fa-sun me-2"></i>
        Claro
      </button>
    </li>
    <li>
      <button
        class="dropdown-item d-flex align-items-center"
        class:active={$tema === 'dark'}
        on:click={tema.activarTemaOscuro}
      >
        <i class="fa-solid fa-moon me-2"></i>
        Oscuro
      </button>
    </li>
  </ul>
</div>

<style>
  .btn-link {
    min-width: 46px;
  }
</style>
