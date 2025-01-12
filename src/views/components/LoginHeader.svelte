<script>
  import theme from "../stores/theme";
  import { Link } from "svelte-routing";
  import ThemeToggler from "./ThemeToggler.svelte";
</script>

<nav class="navbar navbar-expand p-lg-5">
  <div class="container-fluid">
    <a class="navbar-brand" href="./" title="SITCAV" data-bs-toggle="tooltip">
      <img src="./images/favicon.png" alt="Logo de SITCAV" height={55} />
    </a>
    <ul class="navbar-nav ms-auto d-flex">
      <li class="dropdown">
        <button
          class="btn btn-link nav-link bg-transparent"
          class:text-light={$theme === "dark"}
          class:text-dark={$theme === "light"}
          data-bs-toggle="dropdown"
        >
          <i class="fa-solid fa-user fs-1"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <Link class="dropdown-item" to="./ingresar">Ingresar</Link>
          <Link class="dropdown-item" to="./registrarse">Registrarse</Link>
        </div>
      </li>
      <li>
        <ThemeToggler />
      </li>
    </ul>
  </div>
</nav>
