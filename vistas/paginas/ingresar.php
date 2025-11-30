<main class="card">
  <header class="card-header text-center">
    <?php Flight::render('componentes/logo') ?>
    <p class="card-text">Ingrese a su cuenta para continuar</p>
  </header>
  <form method="post" class="card-body d-grid gap-3 needs-validation">
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
      <p class="m-0 px-3 d-inline-block bg-body position-relative z-1">
        o ingresa con
      </p>
      <hr class="position-absolute top-50 start-0 m-0 w-100 translate-middle-y z-0" />
    </div>
    <?php Flight::render('componentes/input', [
      'label' => 'Cédula',
      'name' => 'cedula',
      'required' => true,
      'type' => 'number',
      'value' => 12345678,
    ]) ?>
    <?php Flight::render('componentes/input', [
      'label' => 'Contraseña',
      'name' => 'clave',
      'required' => true,
      'type' => 'password',
      'value' => 'Admin.123',
    ]) ?>
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
