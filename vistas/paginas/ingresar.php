<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center">
        <a href="./" class="d-block pb-3" title="Ir a la página de inicio">
          <img
            src="./recursos/imagenes/logo-horizontal.png"
            class="w-50"
            alt="Logo horizontal de SITCAV" />
        </a>
        <p class="card-text">Menos tiempo, más dinero</p>
      </header>
      <form method="post" class="card-body">
        <div class="mb-3">
          <label for="input-cedula" class="form-label">Cédula</label>
          <input type="number" name="cedula" required class="form-control" id="input-cedula" />
        </div>
        <div class="mb-4">
          <label for="input-clave" class="form-label">Contraseña</label>
          <input type="password" name="clave" required class="form-control" id="input-clave" />
        </div>
        <a class="d-inline-block link-primary fw-bold mb-4 float-end" href="./restablecer-clave">
          ¿Olvidó su contraseña?
        </a>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
          Ingresar
        </button>
        <div class="text-center">
          <span class="fs-4 fw-bold">¿Nuevo en SITCAV?</span>
          <a class="link-primary fw-bold ms-2" href="./registrarse">Crear una cuenta</a>
        </div>
      </form>
    </div>
  </div>
</div>
