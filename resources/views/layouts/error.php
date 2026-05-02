<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="color-scheme" content="light dark">
    <title><?= $title ?></title>
    <base href="<?= BASE_HREF ?>" />
    <link rel="icon" href="./resources/images/favicon.png" />
    <link rel="stylesheet" href="./resources/css/500.css" />
  </head>
  <body>
    <section class="error-page">
      <div class="error-page__card">
        <div class="error-page__icon">!</div>
        <p class="error-page__code"><?= $errorCode ?></p>
        <h1 class="error-page__title"><?= $errorTitle ?></h1>
        <p class="error-page__text">
          <?= $errorText ?>
        </p>

        <div class="error-page__actions">
          <a class="error-page__link" href="./">Volver al inicio</a>
        </div>
      </div>
    </section>

    <script src="./resources/js/500.js"></script>
  </body>
</html>
