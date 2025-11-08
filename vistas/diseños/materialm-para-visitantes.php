<?php

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';

?>

<!doctype html>
<html
  data-bs-theme="<?= session()->get('tema', 'light') ?>"
  data-color-theme="<?= session()->get('tema_colores', 'Blue_Theme') ?>"
  x-data="tema"
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
    href="./recursos/compilados/visitantes.css?id=<?= $idDeRecursos ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= $idDeRecursos ?>" />
  <script src="./recursos/compilados/visitantes.js?id=<?= $idDeRecursos ?>"></script>
  <style>
    body {
      background: url('./recursos/imagenes/Imagen de WhatsApp 2025-10-30 a las 21.55.54_e571a147.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body class="col-xxl-3 col-xl-4 col-lg-5 col-md-6 col-sm-7 pt-3 px-3 mx-auto">
  <?= $pagina ?>
</body>

</html>
