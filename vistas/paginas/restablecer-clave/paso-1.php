<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center" x-data="{ modo: 'correo' }">
        <a href="./" class="d-block pb-3" title="Ir a la página de inicio">
          <img
            src="./recursos/imagenes/logo-horizontal.png"
            class="w-50"
            alt="Logo horizontal de SITCAV" />
        </a>
        <ul class="nav nav-pills nav-fill mb-4">
          <li class="nav-item">
            <a @click="modo = 'correo'" class="nav-link active" data-bs-toggle="tab" href="#restablecer-con-codigo-de-verificacion">
              <span>Mediante código de seguridad</span>
            </a>
          </li>
          <li class="nav-item">
            <a @click="modo = 'cedula'" class="nav-link" data-bs-toggle="tab" href="#restablecer-con-pregunta-y-respuesta-secreta">
              <span>Mediante pregunta y respuesta secreta</span>
            </a>
          </li>
        </ul>
        <p
          class="card-text"
          x-text="modo === 'correo' ? 'Por favor ingresa el correo asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña.' : 'Por favor ingresa tu cédula y te enviaremos la pregunta secreta asociada a tu cuenta.'">
        </p>
      </header>
      <div class="card-body tab-content">
        <form
          method="post"
          action="./restablecer-clave/solicitar-codigo"
          class="tab-pane active"
          id="restablecer-con-codigo-de-verificacion">
          <div class="mb-4">
            <label for="input-correo" class="form-label">Correo</label>
            <input type="email" name="correo" required class="form-control" id="input-correo" />
          </div>
          <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
            Solicitar código de verificación
          </button>
        </form>
        <form method="post" class="tab-pane" id="restablecer-con-pregunta-y-respuesta-secreta">
          <div class="mb-4">
            <label for="input-cedula" class="form-label">Cédula</label>
            <input type="number" name="cedula" required class="form-control" id="input-cedula" />
          </div>
          <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
            Solicitar pregunta secreta
          </button>
        </form>
      </div>
      <footer class="card-footer text-center">
        <span class="fs-4 fw-bold">¿Recordaste tu contraseña?</span>
        <a class="link-primary fw-bold ms-2" href="./ingresar">Ingresar</a>
      </footer>
    </div>
  </div>
</div>
