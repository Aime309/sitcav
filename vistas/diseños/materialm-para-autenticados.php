<?php

use SITCAV\Enums\ClaveSesion;

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';
$errores = (array) flash()->display(ClaveSesion::MENSAJES_ERRORES->name);
$exitos = (array) flash()->display(ClaveSesion::MENSAJES_EXITOS->name);
$advertencias = (array) flash()->display(ClaveSesion::MENSAJES_ADVERTENCIAS->name);
$informaciones = (array) flash()->display(ClaveSesion::MENSAJES_INFORMACIONES->name);

?>

<!doctype html>
<html
  dir="<?= session()->get(ClaveSesion::UI_DIRECCION->name, 'ltr') ?>"
  data-layout="<?= session()->get(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, 'vertical') ?>"
  data-bs-theme="<?= session()->get(ClaveSesion::UI_TEMA->name, '') ?>"
  data-color-theme="<?= session()->get(ClaveSesion::UI_COLORES->name, 'Blue_Theme') ?>"
  data-boxed-layout="<?= session()->get(ClaveSesion::UI_ANCHURA->name, 'boxed') ?>"
  data-sidebartype="<?= session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'mini-sidebar') ?>"
  data-card="<?= session()->get(ClaveSesion::UI_TIPO_TARJETAS->name, 'shadow') ?>"
  x-data="tema"
  :dir="direccion"
  :data-layout="layout"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores"
  :data-boxed-layout="container"
  :data-sidebartype="tipo_menu"
  :data-card="tipo_tarjeta">

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
</head>

<body
  data-sidebartype="<?= session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'full') ?>"
  data-errores='<?= json_encode(array_values($errores)) ?>'
  data-exitos='<?= json_encode(array_values($exitos)) ?>'
  data-advertencias='<?= json_encode(array_values($advertencias)) ?>'
  data-informaciones='<?= json_encode(array_values($informaciones)) ?>'
  x-data="mensajes"
  :data-sidebartype="tipo_menu">
  <?php Flight::render('componentes/notificaciones') ?>
  <?php Flight::render('componentes/barra-busqueda') ?>
  <?php Flight::render('componentes/configuraciones-ui') ?>

  <div id="main-wrapper" x-data="tasaDePagina">
    <?php Flight::render('componentes/menu-navegacion-vertical')
    ?>
    <div class="page-wrapper">
      <?php Flight::render('componentes/menu-superior-v2') ?>
      <?php Flight::render('componentes/menu-navegacion-horizontal') ?>
      <?= $pagina ?>
    </div>
  </div>

  <script src="./recursos/compilados/autenticados.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/theme.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/app.min.js?id=<?= $idDeRecursos ?>"></script>
  <script src="./recursos/js/theme/sidebarmenu.js?id=<?= $idDeRecursos ?>"></script>
</body>

</html>
