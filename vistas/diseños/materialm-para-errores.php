<?php

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';

?>

<!doctype html>
<html
  dir="<?= session()->get('direccion', 'ltr') ?>"
  data-bs-theme="<?= session()->get('tema', '') ?>"
  data-color-theme="<?= session()->get('tema_colores', 'Blue_Theme') ?>"
  data-boxed-layout="<?= session()->get('container', 'boxed') ?>"
  x-data="tema"
  :dir="direccion"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores"
  :data-boxed-layout="container">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="./recursos/compilados/errores.css?id=<?= $idDeRecursos ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= $idDeRecursos ?>" />
  <script src="./recursos/compilados/errores.js?id=<?= $idDeRecursos ?>"></script>
</head>

<body class="text-center"><?= $pagina ?></body>

</html>
