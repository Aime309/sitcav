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
      <form class="card-body" method="post">
        <div class="mb-3">
          <label for="input-cedula" class="form-label">Cédula</label>
          <input type="number" name="cedula" required class="form-control" id="input-cedula" />
        </div>
        <div class="mb-3">
          <label for="input-clave" class="form-label">Contraseña</label>
          <input type="password" name="clave" required class="form-control" id="input-clave" />
        </div>
        <div class="mb-3">
          <label for="input-pregunta-secreta" class="form-label">Pregunta secreta</label>
          <input name="pregunta_secreta" required class="form-control" id="input-pregunta-secreta" />
        </div>
        <div class="mb-4">
          <label for="input-respuesta-secreta" class="form-label">Respuesta secreta</label>
          <input type="password" name="respuesta_secreta" required class="form-control" id="input-respuesta-secreta" />
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
