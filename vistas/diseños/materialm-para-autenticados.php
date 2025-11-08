<?php

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';
$errores = (array) flash()->display('errores');
$exitos = (array) flash()->display('exitos');

?>

<!doctype html>
<html
  dir="<?= session()->get('direccion', 'ltr') ?>"
  data-layout="<?= session()->get('layout', 'vertical') ?>"
  data-bs-theme="<?= session()->get('tema', '') ?>"
  data-color-theme="<?= session()->get('tema_colores', 'Blue_Theme') ?>"
  x-data="tema"
  :dir="direccion"
  :data-layout="layout"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link
    rel="stylesheet"
    href="./recursos/compilados/autenticados.css?id=<?= $idDeRecursos ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= $idDeRecursos ?>" />
  <script src="./recursos/compilados/autenticados.js?id=<?= $idDeRecursos ?>"></script>
</head>

<body
  data-errores='<?= json_encode(array_values($errores)) ?>'
  data-exitos='<?= json_encode(array_values($exitos)) ?>'
  x-data="mensajes">
  <?php Flight::render('componentes/notificaciones') ?>
  <?php # Flight::render('componentes/barra-busqueda')
  ?>
  <?php Flight::render('componentes/configuraciones-ui') ?>

  <div id="main-wrapper" x-data="tasaDePagina">
    <?php # Flight::render('componentes/menu-navegacion-vertical')
    ?>
    <div class="page-wrapper">
      <?php # Flight::render('componentes/menu-superior-v2')
      ?>
      <?php # Flight::render('componentes/menu-navegacion-horizontal')
      ?>
      <?= $pagina ?>
    </div>
  </div>

  <script src="./recursos/js/theme/theme.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/app.min.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/sidebarmenu.js?id=<?= $idDeRecursos ?>"></script>
</body>

</html>
