<script>
  import theme from "../stores/theme";
</script>

<div class="dropdown">
  <button
    class="btn btn-link d-flex align-items-center fs-1"
    class:text-light={$theme === 'dark'}
    class:text-dark={$theme === 'light'}
    data-bs-toggle="dropdown"
  >
    {#if $theme === "dark"}
      <i class="fa-solid fa-moon"></i>
    {:else if $theme === "light"}
      <i class="fa-solid fa-sun"></i>
    {/if}
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <button
        class="dropdown-item d-flex align-items-center"
        class:active={theme.isLight}
        on:click={theme.setLight}
      >
        <i class="fa-solid fa-sun me-2"></i>
        Claro
      </button>
    </li>
    <li>
      <button
        class="dropdown-item d-flex align-items-center"
        class:active={theme.isDark}
        on:click={theme.setDark}
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
