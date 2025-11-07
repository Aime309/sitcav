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
  <link rel="stylesheet" href="./recursos/compilados/errores.css" />
  <link rel="stylesheet" href="./recursos/css/materialm.css" />
  <script src="./recursos/compilados/errores.js"></script>
</head>

<body class="text-center">
  <?= $pagina ?>
</body>

</html>
