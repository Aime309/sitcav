<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center">
        <?php Flight::render('componentes/enlace-logo') ?>
        <p class="card-text">
          Para restablecer tu contraseña, responde la siguiente pregunta secreta:
        </p>
      </header>
      <form action="./restablecer-clave/2" method="post" class="card-body">
        <div class="mb-4">
          <label for="input-respuesta-secreta" class="form-label"><?= $usuario->pregunta_secreta ?></label>
          <input type="password" name="respuesta_secreta" required class="form-control" id="input-respuesta-secreta" placeholder="Respuesta secreta" />
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
          Cambiar contraseña
        </button>
      </form>
      <footer class="card-footer text-center">
        <span class="fs-4 fw-bold">¿Recordaste tu contraseña?</span>
        <a class="link-primary fw-bold ms-2" href="./ingresar">Ingresar</a>
      </footer>
    </div>
  </div>
</div>
