<main class="card mb-3">
  <header class="card-header text-center">
    <?php Flight::render('componentes/logo') ?>
    <p class="card-text">Crea una cuenta para comenzar</p>
  </header>
  <form class="card-body d-grid gap-3" method="post">
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
        o regístrate con
      </p>
      <hr class="position-absolute top-50 start-0 m-0 w-100 translate-middle-y z-0" />
    </div>
    <div>
      <label class="form-label">Cédula</label>
      <input type="number" name="cedula" required class="form-control" />
    </div>
    <div>
      <?php Flight::render('componentes/input-clave', [
        'label' => 'Contraseña',
        'name' => 'clave',
        'required' => true,
      ]) ?>
    </div>
    <div>
      <label class="form-label">Pregunta secreta</label>
      <input name="pregunta_secreta" required class="form-control" />
    </div>
    <div>
      <?php Flight::render('componentes/input-clave', [
        'label' => 'Respuesta secreta',
        'name' => 'respuesta_secreta',
        'required' => true,
        'mostrarAdvertencias' => false,
      ]) ?>
    </div>
    <button class="btn btn-primary">Registrarse</button>
  </form>
  <footer class="card-footer text-center">
    <p class="m-0 d-inline-block">¿Ya tienes una cuenta?</p>
    <a class="link-primary link-offset-3" href="./ingresar">Ingresar</a>
  </footer>
</main>
