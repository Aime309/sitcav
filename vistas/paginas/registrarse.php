<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center">
        <?php Flight::render('componentes/enlace-logo') ?>
        <p class="card-text">
          Crea una cuenta para comenzar
        </p>
      </header>
      <form class="card-body" method="post">
        <div class="row">
          <div class="col-12 mb-2 mb-sm-0">
            <a
              class="btn btn-link border border-muted d-flex align-items-center justify-content-center rounded-2 py-8 text-decoration-none"
              href="./oauth2/google">
              <img
                src="./recursos/imagenes/svgs/google-icon.svg"
                alt="MaterialM-img"
                class="img-fluid me-2"
                width="18"
                height="18" />
              Google
            </a>
          </div>
        </div>
        <div class="position-relative text-center my-4">
          <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-3 position-relative">
            o regístrate con
          </p>
          <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
        </div>
        <div class="mb-3">
          <label for="input-cedula" class="form-label">Cédula</label>
          <input type="number" name="cedula" required class="form-control" id="input-cedula" />
        </div>
        <div class="mb-3">
          <?php Flight::render('componentes/input-clave', [
            'label' => 'Contraseña',
            'name' => 'clave',
            'required' => true,
          ]) ?>
        </div>
        <div class="mb-3">
          <label for="input-pregunta-secreta" class="form-label">Pregunta secreta</label>
          <input name="pregunta_secreta" required class="form-control" id="input-pregunta-secreta" />
        </div>
        <div class="mb-4">
          <?php Flight::render('componentes/input-clave', [
            'label' => 'Respuesta secreta',
            'name' => 'respuesta_secreta',
            'required' => true,
            'mostrarAdvertencias' => false,
          ]) ?>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
          Registrarse
        </button>
        <div class="text-center">
          <span class="fs-4 fw-bold">¿Ya tienes una cuenta?</span>
          <a class="link-primary fw-bold ms-2" href="./ingresar">Ingresar</a>
        </div>
      </form>
    </div>
  </div>
</div>
