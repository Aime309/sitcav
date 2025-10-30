<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="./recursos/imagenes/favicon.png" />

  <!-- Core Css -->
  <link rel="stylesheet" href="./recursos/css/styles.css" />
  <title>MaterialM Bootstrap Admin</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="./recursos/imagenes/favicon.png" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-lg-4">
            <div class="text-center">
              <img src="./recursos/imagenes/backgrounds/maintenance.svg" alt="MaterialM-img" class="img-fluid" width="500">
              <h1 class="fw-semibold my-7 fs-9">
                ¡¡¡Modo de Mantenimiento!!!
              </h1>
              <h4 class="fw-semibold mb-7">
                El sitio web está en mantenimiento. ¡Vuelve más tarde!
              </h4>
              <a class="btn btn-primary" href="./" role="button">
                Regresar al Inicio
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <!-- Import Js Files -->
  <script src="./recursos/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./recursos/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="./recursos/js/theme/app.init.js"></script>
  <script src="./recursos/js/theme/theme.js"></script>
  <script src="./recursos/js/theme/app.min.js"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
