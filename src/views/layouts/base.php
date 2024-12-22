<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?? 'SITCAV' ?></title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./images/favicon.png" />
  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="./build/bundle.css">
  <script defer src="./build/bundle.js"></script>
</head>

<body>
  <!-- Here will be rendered App.svelte, check main.js ➡ target -->
</body>

</html>
