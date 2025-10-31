<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center">
        <?php Flight::render('componentes/enlace-logo') ?>
        <p class="card-text">
          Ingrese a su cuenta para continuar
        </p>
      </header>
      <form method="post" class="card-body">
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
            o ingresa con
          </p>
          <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
        </div>
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
