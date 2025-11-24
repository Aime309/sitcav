<?php

use SITCAV\Enums\ClaveSesion;

$errores = (array) session()->retrieve(ClaveSesion::MENSAJES_ERRORES->name, flash()->display(ClaveSesion::MENSAJES_ERRORES->name));
$exitos = (array) session()->retrieve(ClaveSesion::MENSAJES_EXITOS->name, flash()->display(ClaveSesion::MENSAJES_EXITOS->name));
$advertencias = (array) session()->retrieve(ClaveSesion::MENSAJES_ADVERTENCIAS->name, flash()->display(ClaveSesion::MENSAJES_ADVERTENCIAS->name));
$informaciones = (array) session()->retrieve(ClaveSesion::MENSAJES_INFORMACIONES->name, flash()->display(ClaveSesion::MENSAJES_INFORMACIONES->name));

?>

<!doctype html>
<html
  dir="<?= session()->get(ClaveSesion::UI_DIRECCION->name, 'ltr') ?>"
  data-bs-theme="<?= session()->get(ClaveSesion::UI_TEMA->name, '') ?>"
  data-color-theme="<?= session()->get(ClaveSesion::UI_COLORES->name, 'Blue_Theme') ?>"
  data-boxed-layout="<?= session()->get(ClaveSesion::UI_ANCHURA->name, 'boxed') ?>"
  data-card="<?= session()->get(ClaveSesion::UI_TIPO_TARJETAS->name, 'shadow') ?>"
  x-data="tema"
  :dir="direccion"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores"
  :data-boxed-layout="container"
  :data-card="tipo_tarjeta">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?? '' ?> | SITCAV</title>
  <base href="<?= BASE_HREF ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link
    rel="stylesheet"
    href="./recursos/compilados/visitantes.css?id=<?= ID_DE_RECURSOS ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= ID_DE_RECURSOS ?>" />
  <style>
    body {
      background: url('./recursos/imagenes/Imagen de WhatsApp 2025-10-30 a las 21.55.54_e571a147.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body
  data-errores='<?= json_encode(array_values($errores)) ?>'
  data-exitos='<?= json_encode(array_values($exitos)) ?>'
  data-advertencias='<?= json_encode(array_values($advertencias)) ?>'
  data-informaciones='<?= json_encode(array_values($informaciones)) ?>'
  x-data="mensajes"
  class="col-xxl-3 col-xl-4 col-lg-5 col-md-6 col-sm-7 pt-3 px-3 mx-auto">
  <?php Flight::render('componentes/notificaciones') ?>

  <?php Flight::render('componentes/configuraciones-ui', [
    'mostrarLayouts' => false,
    'mostrarTiposMenu' => false,
  ]) ?>

  <?= $pagina ?>
  <script src="./recursos/compilados/visitantes.js?id=<?= ID_DE_RECURSOS ?>"></script>
</body>

</html>
