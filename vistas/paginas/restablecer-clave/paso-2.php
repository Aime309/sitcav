<main class="card">
  <header class="card-header text-center">
    <?php Flight::render('componentes/enlace-logo') ?>
    <p class="card-text">
      Para restablecer tu contraseña, responde la siguiente pregunta secreta:
    </p>
  </header>
  <form action="./restablecer-clave/2" method="post" class="card-body d-grid gap-3">
    <div>
      <label class="form-label"><?= $usuario->pregunta_secreta ?></label>
      <input
        type="password"
        name="respuesta_secreta"
        required
        class="form-control"
        placeholder="Respuesta secreta" />
    </div>
    <button class="btn btn-primary">Cambiar contraseña</button>
  </form>
  <footer class="card-footer text-center">
    <p class="d-inline-block m-0">¿Recordaste tu contraseña?</p>
    <a class="link-primary link-offset-3" href="./ingresar">Ingresar</a>
  </footer>
</main>
