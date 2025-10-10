<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="./recursos/compilados/visitantes.css" />
</head>

<body class="text-bg-light">
  <?= $pagina ?>

  <?php Flight::render('componentes/notificaciones') ?>
  <script defer src="./recursos/compilados/visitantes.js"></script>
</body>

</html>
