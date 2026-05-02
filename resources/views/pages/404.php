<style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
  }

  .error-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  }

  .error-page__card {
    width: 100%;
    max-width: 560px;
    background: #ffffff;
    border-radius: 20px;
    padding: 48px 36px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  }

  .error-page__icon {
    width: 84px;
    height: 84px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fee2e2;
    color: #dc2626;
    font-size: 2rem;
    font-weight: 700;
  }

  .error-page__code {
    margin: 0 0 8px;
    color: #2563eb;
    font-size: 3rem;
    line-height: 1;
  }

  .error-page__title {
    margin: 0 0 12px;
    color: #1f2937;
    font-size: 1.8rem;
  }

  .error-page__text {
    margin: 0 0 28px;
    color: #6b7280;
    font-size: 1rem;
    line-height: 1.6;
  }

  .error-page__actions {
    display: flex;
    justify-content: center;
  }

  .error-page__link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 24px;
    border-radius: 10px;
    background: #2563eb;
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
  }

  .error-page__link:hover {
    background: #1d4ed8;
  }
</style>

<section class="error-page">
  <div class="error-page__card">
    <div class="error-page__icon">!</div>
    <p class="error-page__code">404</p>
    <h1 class="error-page__title">Pagina no encontrada</h1>
    <p class="error-page__text">
      La ruta que intentaste abrir no existe o ya no esta disponible.
    </p>

    <div class="error-page__actions">
      <a class="error-page__link" href="<?= BASE_HREF ?>">Volver al inicio</a>
    </div>
  </div>
</section>
