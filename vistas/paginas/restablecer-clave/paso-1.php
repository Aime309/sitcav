<main class="card">
  <header class="card-header text-center" x-data="{ modo: 'correo' }">
    <?php Flight::render('componentes/logo') ?>
    <ul class="nav nav-pills nav-fill mb-3">
      <li class="nav-item">
        <a
          @click="modo = 'correo'"
          class="nav-link active"
          data-bs-toggle="tab"
          href="#restablecer-con-codigo-de-verificacion">
          <span>Mediante código de seguridad</span>
        </a>
      </li>
      <li class="nav-item">
        <a
          @click="modo = 'cedula'"
          class="nav-link"
          data-bs-toggle="tab"
          href="#restablecer-con-pregunta-y-respuesta-secreta">
          <span>Mediante pregunta y respuesta secreta</span>
        </a>
      </li>
    </ul>
    <p
      class="card-text"
      x-text="
        modo === 'correo'
          ? 'Por favor ingresa el correo asociado a tu cuenta y te enviaremos un enlace para restablecer tu contraseña.'
          : 'Por favor ingresa tu cédula y te enviaremos la pregunta secreta asociada a tu cuenta.'
      ">
    </p>
  </header>
  <div class="card-body tab-content">
    <div class="tab-pane active" id="restablecer-con-codigo-de-verificacion">
      <form
        method="post"
        action="./restablecer-clave/solicitar-codigo"
        class="d-grid gap-3">
        <div>
          <label class="form-label">Correo</label>
          <input type="email" name="correo" required class="form-control" />
        </div>
        <button class="btn btn-primary">
          Solicitar código de verificación
        </button>
      </form>
    </div>
    <div class="tab-pane" id="restablecer-con-pregunta-y-respuesta-secreta">
      <form method="post" class="d-grid gap-3">
        <div>
          <label class="form-label">Cédula</label>
          <input type="number" name="cedula" required class="form-control" />
        </div>
        <button class="btn btn-primary">Solicitar pregunta secreta</button>
      </form>
    </div>
  </div>
  <footer class="card-footer text-center">
    <p class="d-inline-block m-0">¿Recordaste tu contraseña?</p>
    <a class="link-primary link-offset-3" href="./ingresar">Ingresar</a>
  </footer>
</main>
