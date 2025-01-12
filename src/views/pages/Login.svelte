<script>
  import "bootstrap/dist/css/bootstrap.min.css";
  import "@fortawesome/fontawesome-free/css/all.min.css";
  import "bootstrap/js/dist/dropdown";
  import { Link } from "svelte-routing";
  import ThemeToggler from "../components/ThemeToggler.svelte";
  import theme from "../stores/theme";
  import title from "../stores/title";

  $title = "Ingresar";
</script>

<main
  class="min-vh-100"
  style:background-image="url(./images/banner@{$theme}.png)"
>
  <nav class="navbar navbar-expand p-5">
    <div class="container-fluid">
      <a class="navbar-brand" href="./">
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

  <div class="container mt-5">
    <div class="row align-items-center">
      <div class="col-sm-9 col-md-6">
        <h1 class="fw-bold text-primary">
          SITCAV
          <div class="d-none d-sm-inline-flex align-items-end">
            <i class="fa-solid fa-computer fs-1"></i>
            <i class="fa-solid fa-laptop fs-2"></i>
            <small class="fa-solid fa-tablet fs-3"></small>
            <small class="fa-solid fa-mobile fs-4"></small>
          </div>
        </h1>
        <h2 class="fw-bold">
          Sistema Integral de Tecnología y Análisis de Ventas
        </h2>
      </div>
      <div class="d-none d-md-flex col-sm-3 col-md-3">
        <img
          src="./images/favicon.png"
          alt="Logo de SITCAV"
          class="img-fluid"
        />
      </div>
      <div class="col-md-3">
        <form method="post">
          <h3 class="text-center">Iniciar sesión</h3>
          <input
            type="number"
            name="idCard"
            required
            min={0}
            class="form-control rounded-0 my-3"
            placeholder="Cédula"
            value={12345678}
          />
          <input
            type="password"
            name="password"
            required
            class="form-control rounded-0 my-3"
            placeholder="Contraseña"
            value="admin123"
          />
          <button class="btn rounded-0 w-100 text-light fw-bold">
            Ingresar
          </button>
        </form>
      </div>
    </div>
  </div>
</main>

<style>
  main {
    background-size: cover;
  }

  h1 {
    font-size: 60px;
  }

  button {
    background: #222b3c;

    &:hover {
      background: black;
    }
  }

  img {
    max-height: 170px;
  }

  :global(.dropdown-menu) {
    --bs-dropdown-min-width: unset;
  }
</style>
