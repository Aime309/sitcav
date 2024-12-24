<script>
  import "bootstrap/dist/css/bootstrap.min.css";
  import "@fortawesome/fontawesome-free/css/all.min.css";
</script>

<main>
  <div class="container">
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
          <h3 class="d-none d-md-block text-light text-center">
            Iniciar sesión
          </h3>
          <h3 class="d-md-none text-center">Iniciar sesión</h3>
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
    background-image: url("../images/banner.png");
    min-height: 100vh;
    display: grid;
    place-content: center;
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
</style>
