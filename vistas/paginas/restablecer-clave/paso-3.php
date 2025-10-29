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
        <p class="card-text">
          Ingresa tu nueva contraseña a continuación:
        </p>
      </header>
      <form action="./restablecer-clave/3" method="post" class="card-body">
        <div class="mb-4">
          <?php Flight::render('componentes/input-clave', [
            'label' => 'Nueva contraseña',
            'name' => 'nueva_clave',
            'required' => true,
          ]) ?>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
          Restablecer
        </button>
      </form>
      <footer class="card-footer text-center">
        <span class="fs-4 fw-bold">¿Recordaste tu contraseña?</span>
        <a class="link-primary fw-bold ms-2" href="./ingresar">Ingresar</a>
      </footer>
    </div>
  </div>
</div>
