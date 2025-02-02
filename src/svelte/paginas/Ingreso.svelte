<script>
  import tema from "../globales/tema";
  import titulo from "../globales/titulo";
  import MenuPublico from "../componentes/MenuPublico.svelte";
  import Icono from "../componentes/Icono.svelte";

  $titulo = "Ingresar";
</script>

<main
  class="min-vh-100"
  style:background-image="url('./imagenes/banner@{$tema}.png')"
>
  <MenuPublico />

  <div class="container mt-5">
    <div class="row align-items-center">
      <div class="col-sm-9 col-md-6">
        <h1 class="fw-bold text-primary">
          SITCAV
          <div class="d-none d-sm-inline-flex align-items-end">
            <Icono nombre="computer" class="fs-1" />
            <Icono nombre="laptop" class="fs-2" />
            <Icono nombre="tablet" class="fs-3" />
            <Icono nombre="mobile" class="fs-4" />
          </div>
        </h1>
        <h2 class="fw-bold">
          Sistema Integral de Tecnología y Análisis de Ventas
        </h2>
      </div>
      <div class="d-none d-md-flex col-sm-3 col-md-3">
        <img
          src="./imagenes/favicon.png"
          alt="Logo de SITCAV"
          class="img-fluid"
        />
      </div>
      <div class="col-md-3">
        <form method="post">
          <h3 class="text-center">Iniciar sesión</h3>
          <input
            type="number"
            name="cedula"
            required
            min={0}
            class="form-control rounded-0 my-3"
            placeholder="Cédula"
          />
          <input
            type="password"
            name="clave"
            required
            class="form-control rounded-0 my-3"
            placeholder="Contraseña"
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
