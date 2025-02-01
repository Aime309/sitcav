<script>
  import tema from "../globales/tema";
  import { Link } from "svelte-routing";
  import AlternadorDelTema from "./AlternadorDelTema.svelte";
</script>

<nav class="navbar navbar-expand sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="./" title="SITCAV" data-bs-toggle="tooltip">
      <img src="./imagenes/favicon.png" alt="Logo de SITCAV" height={55} />
    </a>
    <ul class="navbar-nav ms-auto d-flex">
      <li class="dropdown">
        <button
          class="btn btn-link nav-link bg-transparent"
          class:text-light={$tema === "dark"}
          class:text-dark={$tema === "light"}
          data-bs-toggle="dropdown"
        >
          <i class="fa-solid fa-user fs-1"></i>
          <span class="visually-hidden">Acceso al sistema</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <Link class="dropdown-item" to="./ingresar">Ingresar</Link>
          <Link class="dropdown-item" to="./registrarse">Registrarse</Link>
        </div>
      </li>
      <li>
        <AlternadorDelTema />
      </li>
    </ul>
  </div>
</nav>
