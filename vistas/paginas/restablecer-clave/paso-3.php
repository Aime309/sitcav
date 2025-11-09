<main class="card">
  <header class="card-header text-center">
    <?php Flight::render('componentes/logo') ?>
    <p class="card-text">Ingresa tu nueva contraseña a continuación:</p>
  </header>
  <form action="./restablecer-clave/3" method="post" class="card-body d-grid gap-3">
    <div>
      <?php Flight::render('componentes/input-clave', [
        'label' => 'Nueva contraseña',
        'name' => 'nueva_clave',
        'required' => true,
      ]) ?>
    </div>
    <button class="btn btn-primary">Restablecer</button>
  </form>
  <footer class="card-footer text-center">
    <p class="d-inline-block m-0">¿Recordaste tu contraseña?</p>
    <a class="link-primary link-offset-3" href="./ingresar">Ingresar</a>
  </footer>
</main>
