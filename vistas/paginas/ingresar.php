<main class="card">
  <header class="card-header text-center">
    <?php Flight::render('componentes/enlace-logo') ?>
    <p class="card-text">Ingrese a su cuenta para continuar</p>
  </header>
  <form method="post" class="card-body d-grid gap-3">
    <a
      class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2"
      href="./oauth2/google">
      <img
        src="./recursos/imagenes/google.ico"
        class="img-fluid"
        style="width: 1em; height: 1em" />
      Google
    </a>
    <div class="position-relative text-center">
      <p class="m-0 px-3 d-inline-block bg-body position-relative z-1">o ingresa con</p>
      <hr class="position-absolute top-50 start-0 m-0 w-100 translate-middle-y z-0" />
    </div>
    <div>
      <label class="form-label">Cédula</label>
      <input
        class="form-control"
        name="cedula"
        required
        type="number" />
    </div>
    <div>
      <label class="form-label">Contraseña</label>
      <input
        class="form-control"
        name="clave"
        required
        type="password" />
    </div>
    <a class="d-inline-block ms-auto link-primary link-offset-3" href="./restablecer-clave">
      ¿Olvidó su contraseña?
    </a>
    <button class="btn btn-primary">Ingresar</button>
  </form>
  <footer class="card-footer text-center">
    <p class="d-inline-block m-0">¿Nuevo en SITCAV?</p>
    <a class="link-primary link-offset-3" href="./registrarse">Crear una cuenta</a>
  </footer>
</main>
