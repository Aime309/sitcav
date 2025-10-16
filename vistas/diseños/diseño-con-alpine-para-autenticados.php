<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="./recursos/css/materialm.min.css" />
  <link rel="stylesheet" href="./recursos/compilados/visitantes.css" />
</head>

<body>
  <?php Flight::render('componentes/indicador-cargando-pagina') ?>

  <!--  Body Wrapper -->
  <div
    class="page-wrapper"
    id="main-wrapper"
    data-layout="vertical"
    data-sidebartype="full"
    data-sidebar-position="fixed"
    data-header-position="fixed">
    <?php Flight::render('componentes/menu-navegacion') ?>
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <?php Flight::render('componentes/menu-superior') ?>
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <?= $pagina ?>
          <?php Flight::render('componentes/pie-de-pagina') ?>
        </div>
      </div>
    </div>
  </div>

  <?php Flight::render('componentes/notificaciones') ?>

  <script defer src="./recursos/compilados/autenticados.js"></script>
  <script defer src="./recursos/js/sidebarmenu.js"></script>
  <script defer src="./recursos/js/app.min.js"></script>
</body>

</html>
