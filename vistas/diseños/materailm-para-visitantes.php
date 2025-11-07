<?php

$errores = (array) flash()->display('errores');
$exitos = (array) flash()->display('exitos');

?>

<!doctype html>
<html
  x-data='{
    tema: matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light",
  }'
  x-init="
    matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (evento) => {
      tema = evento.matches ? 'dark' : 'light';
    });
  "
  x-effect="
    fetch('./api/ajustes/tema', {
      method: 'post',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ tema })
    });
  "
  data-bs-theme="<?= session()->get('tema', 'light') ?>"
  :data-bs-theme="tema">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="./recursos/css/styles.css" />
  <link rel="stylesheet" href="./recursos/compilados/visitantes.css" />
  <script src="./recursos/compilados/visitantes.js"></script>

  <style>
    body {
      background: url('./recursos/imagenes/Imagen de WhatsApp 2025-10-30 a las 21.55.54_e571a147.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body
  :class="`text-bg-${tema}`"
  x-data="SITCAV"
  data-errores='<?= json_encode(array_values($errores)) ?>'
  data-exitos='<?= json_encode(array_values($exitos)) ?>'>
  <?php Flight::render('componentes/indicador-cargando-pagina') ?>
  <?= $pagina ?>

  <?php Flight::render('componentes/notificaciones') ?>
</body>

</html>
