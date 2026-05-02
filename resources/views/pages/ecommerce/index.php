<?php

declare(strict_types=1);

use App\Enums\Role;
use flight\Container;
use Leaf\Auth;

$authenticatedUser = Container::getInstance()->get(Auth::class)->user();
$ecommerceAuthenticatedUser = null;

if ($authenticatedUser !== null) {
  $rawRoles = $authenticatedUser->roles ?? null;
  $roles = [];

  if (is_string($rawRoles) && $rawRoles !== '') {
    $decodedRoles = json_decode($rawRoles, true);
    $roles = is_array($decodedRoles) ? $decodedRoles : [$rawRoles];
  }

  if (in_array(Role::CLIENT->name, $roles, true)) {
    $names = trim(strval($authenticatedUser->names ?? ''));
    $lastnames = trim(strval($authenticatedUser->lastnames ?? ''));
    $fullName = trim("$names $lastnames");
    $avatar = $authenticatedUser->avatar ?? $authenticatedUser->foto_url ?? null;

    $ecommerceAuthenticatedUser = [
      'id' => strval($authenticatedUser->id),
      'nombre' => $fullName !== '' ? $fullName : 'Cliente',
      'roles' => 'Cliente',
      'email' => $authenticatedUser->email ?? null,
      'foto_url' => is_string($avatar) ? $avatar : null,
      'isAuthenticatedClient' => true,
    ];
  }
}
?>
<!doctype html>
<html lang="es">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="color-scheme" content="light dark" />
    <title>SITCAV Ecommerce</title>
    <base href="<?= BASE_HREF ?>" />
    <link rel="icon" type="image/png" href="./resources/images/favicon.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="./resources/css/index.css?id=<?= RESOURCES_ID ?>" />
    <style>
      :root {
        color-scheme: light dark;
        --storefront-bg: #f5f7fb;
        --storefront-surface: #ffffff;
        --storefront-surface-muted: #f9fafb;
        --storefront-border: #e5e7eb;
        --storefront-text: #111827;
        --storefront-muted: #6b7280;
        --storefront-text-soft: #374151;
        --storefront-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        --storefront-image-bg: #e5e7eb;
        --storefront-carousel-btn-bg: #ffffff;
        --storefront-carousel-btn-border: #2563eb;
        --storefront-carousel-btn-text: #2563eb;
        --storefront-carousel-btn-hover-bg: #2563eb;
        --storefront-carousel-btn-hover-text: #ffffff;
        --storefront-logout-bg: #ef4444;
        --storefront-logout-hover-bg: #dc2626;
        --storefront-logout-text: #ffffff;
        --storefront-modal-backdrop: rgba(15, 23, 42, 0.55);
      }

      @media (prefers-color-scheme: dark) {
        :root {
          --storefront-bg: #020617;
          --storefront-surface: #111827;
          --storefront-surface-muted: #1f2937;
          --storefront-border: #334155;
          --storefront-text: #f8fafc;
          --storefront-muted: #cbd5e1;
          --storefront-text-soft: #e2e8f0;
          --storefront-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
          --storefront-image-bg: #334155;
          --storefront-carousel-btn-bg: #1e293b;
          --storefront-carousel-btn-border: #60a5fa;
          --storefront-carousel-btn-text: #bfdbfe;
          --storefront-carousel-btn-hover-bg: #60a5fa;
          --storefront-carousel-btn-hover-text: #0f172a;
          --storefront-logout-bg: #ef4444;
          --storefront-logout-hover-bg: #f87171;
          --storefront-logout-text: #ffffff;
          --storefront-modal-backdrop: rgba(2, 6, 23, 0.78);
        }
      }

      body {
        background: var(--storefront-bg);
        color: var(--storefront-text);
        display: block;
        width: 100%;
        min-height: 100vh;
        margin: 0;
      }

      .storefront-shell {
        min-height: 100vh;
        width: 100%;
      }

      .storefront-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 24px;
        background: var(--storefront-surface);
        border-bottom: 1px solid var(--storefront-border);
        position: sticky;
        top: 0;
        z-index: 10;
      }

      .storefront-brand {
        display: flex;
        align-items: center;
        gap: 14px;
      }

      .storefront-brand-copy h1 {
        margin: 0;
        font-size: 1.4rem;
        color: var(--storefront-text);
      }

      .storefront-brand-copy p {
        margin: 4px 0 0;
        color: var(--storefront-muted);
      }

      .storefront-actions {
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .storefront-user {
        color: var(--storefront-text-soft);
        font-weight: 600;
      }

      .storefront-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 24px 56px;
      }

      .storefront-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.7fr);
        gap: 24px;
        align-items: stretch;
        margin-bottom: 32px;
      }

      .storefront-hero-card,
      .storefront-summary-card {
        background: var(--storefront-surface);
        border-radius: 18px;
        padding: 28px;
        box-shadow: var(--storefront-shadow);
      }

      .storefront-hero-card h2 {
        margin: 0 0 12px;
        font-size: 2rem;
        line-height: 1.15;
        color: var(--storefront-text);
      }

      .storefront-hero-card p,
      .storefront-summary-card p {
        color: var(--storefront-muted);
      }

      .storefront-summary-card h3,
      .storefront-summary-item strong {
        color: var(--storefront-text);
      }

      .storefront-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
      }

      .storefront-summary-list {
        display: grid;
        gap: 14px;
        margin-top: 20px;
      }

      .storefront-summary-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
      }

      .storefront-summary-item i {
        color: var(--primary-color);
        margin-top: 4px;
      }

      .storefront-section-header {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
      }

      .storefront-section-header h3 {
        margin: 0;
        color: var(--storefront-text);
      }

      .storefront-section-header p {
        margin: 6px 0 0;
        color: var(--storefront-muted);
      }

      .carousel-container {
        background: var(--storefront-surface);
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--storefront-shadow);
      }

      .carousel {
        overflow: hidden;
      }

      .carousel-btn {
        background: var(--storefront-carousel-btn-bg);
        color: var(--storefront-carousel-btn-text);
        border-color: var(--storefront-carousel-btn-border);
      }

      .carousel-btn:hover {
        background: var(--storefront-carousel-btn-hover-bg);
        color: var(--storefront-carousel-btn-hover-text);
        border-color: var(--storefront-carousel-btn-hover-bg);
      }

      .storefront-logout-btn {
        background: var(--storefront-logout-bg);
        color: var(--storefront-logout-text);
      }

      .storefront-logout-btn:hover {
        background: var(--storefront-logout-hover-bg);
        color: var(--storefront-logout-text);
      }

      .carousel-track {
        display: flex;
        gap: 20px;
        transition: transform 0.25s ease;
        will-change: transform;
      }

      .product-card {
        min-width: 250px;
        max-width: 250px;
        background: var(--storefront-surface-muted);
        border: 1px solid var(--storefront-border);
        border-radius: 16px;
        padding: 16px;
      }

      .product-image {
        width: 100%;
        height: 190px;
        object-fit: cover;
        border-radius: 12px;
        background: var(--storefront-image-bg);
        margin-bottom: 12px;
      }

      .product-card h4 {
        margin: 0 0 8px;
        min-height: 48px;
        color: var(--storefront-text);
      }

      .product-description {
        min-height: 40px;
        color: var(--storefront-muted);
        font-size: 0.92rem;
      }

      .product-price {
        margin-top: 14px;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
      }

      .product-stock {
        margin-top: 8px;
        color: var(--storefront-muted);
        font-size: 0.9rem;
      }

      .storefront-empty {
        text-align: center;
        color: var(--storefront-muted);
        padding: 32px 16px;
      }

      .storefront-modal {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: var(--storefront-modal-backdrop);
        z-index: 100;
      }

      .storefront-modal.is-open {
        display: flex;
      }

      .storefront-modal__card {
        width: 100%;
        max-width: 640px;
        background: var(--storefront-surface);
        color: var(--storefront-text);
        border: 1px solid var(--storefront-border);
        border-radius: 20px;
        box-shadow: var(--storefront-shadow);
        padding: 24px;
      }

      .storefront-modal__title {
        margin: 0 0 12px;
        font-size: 1.4rem;
      }

      .storefront-modal__text,
      .storefront-modal__list {
        color: var(--storefront-muted);
      }

      .storefront-modal__list {
        margin: 16px 0 0;
        padding-left: 20px;
        line-height: 1.6;
      }

      .storefront-modal__actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
      }

      @media (max-width: 900px) {
        .storefront-hero {
          grid-template-columns: 1fr;
        }
      }

      @media (max-width: 640px) {
        .storefront-header {
          flex-direction: column;
          align-items: stretch;
        }

        .storefront-actions {
          justify-content: space-between;
        }

        .storefront-main {
          padding-inline: 16px;
        }
      }
    </style>
  </head>

  <body>
    <div class="storefront-shell">
      <header class="storefront-header">
        <div class="storefront-brand">
          <div class="logo-icon">
            <img src="./resources/images/favicon.png" alt="SITCAV" />
          </div>
          <div class="storefront-brand-copy">
            <h1>SITCAV Ecommerce</h1>
            <p>Explora productos y compra desde una experiencia publica.</p>
          </div>
        </div>

        <div class="storefront-actions">
          <?php if ($ecommerceAuthenticatedUser !== null): ?>
            <span class="storefront-user">
              Hola, <?= htmlspecialchars($ecommerceAuthenticatedUser['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          <?php endif; ?>

          <a
            href="<?= $ecommerceAuthenticatedUser !== null ? 'salir' : '#' ?>"
            <?= $ecommerceAuthenticatedUser === null ? 'data-google-login-trigger="true"' : '' ?>
            class="btn <?= $ecommerceAuthenticatedUser !== null ? 'storefront-logout-btn' : 'btn-primary' ?>">
            <i class="<?= $ecommerceAuthenticatedUser !== null ? 'fas fa-sign-out-alt' : 'fab fa-google' ?>"></i>
            <?= $ecommerceAuthenticatedUser !== null ? 'Cerrar Sesión' : 'Iniciar Sesión' ?>
          </a>
        </div>
      </header>

      <main class="storefront-main">
        <section class="storefront-hero">
          <article class="storefront-hero-card">
            <h2>Encuentra tecnologia, accesorios y equipos listos para entrega.</h2>
            <p>
              Explora el catalogo, revisa disponibilidad y accede con tu cuenta para
              continuar tu experiencia de compra.
            </p>

            <div class="storefront-hero-actions">
              <a href="#productos-destacados" class="btn btn-primary">
                <i class="fas fa-fire"></i>
                Ver productos destacados
              </a>
            </div>
          </article>

          <aside class="storefront-summary-card">
            <h3>Compra mas facil</h3>
            <p>
              Accede con Google para gestionar tu sesion como cliente y navegar el
              catalogo sin cargar el panel interno.
            </p>

            <div class="storefront-summary-list">
              <div class="storefront-summary-item">
                <i class="fas fa-bolt"></i>
                <div>
                  <strong>Catalogo visible</strong>
                  <p>Productos destacados cargados directamente desde la API.</p>
                </div>
              </div>
              <div class="storefront-summary-item">
                <i class="fas fa-shield-alt"></i>
                <div>
                  <strong>Acceso cliente</strong>
                  <p>Inicia sesion con Google para identificarte rapidamente.</p>
                </div>
              </div>
              <div class="storefront-summary-item">
                <i class="fas fa-mobile-alt"></i>
                <div>
                  <strong>Vista liviana</strong>
                  <p>Una experiencia enfocada en productos, precios y disponibilidad.</p>
                </div>
              </div>
            </div>
          </aside>
        </section>

        <section id="productos-destacados" class="carousel-container">
          <div class="storefront-section-header">
            <div>
              <h3><i class="fas fa-fire"></i> Productos destacados</h3>
              <p>Seleccionados desde el inventario actual disponible.</p>
            </div>

            <div class="carousel-controls">
              <button type="button" class="carousel-btn" onclick="moveCarousel(-1)">
                <i class="fas fa-chevron-left"></i>
              </button>
              <button type="button" class="carousel-btn" onclick="moveCarousel(1)">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>

          <div class="carousel">
            <div class="carousel-track" id="carousel-track"></div>
          </div>

          <div id="storefront-empty" class="storefront-empty" hidden>
            No hay productos disponibles en este momento.
          </div>
        </section>
      </main>
    </div>

    <div id="terms-modal" class="storefront-modal" aria-hidden="true">
      <div class="storefront-modal__card" role="dialog" aria-modal="true" aria-labelledby="terms-modal-title">
        <h2 id="terms-modal-title" class="storefront-modal__title">Términos y condiciones de SITCAV</h2>
        <p class="storefront-modal__text">
          Antes de iniciar sesión, confirma que aceptas las condiciones básicas de uso de SITCAV:
        </p>
        <ul class="storefront-modal__list">
          <li>Usarás la plataforma de forma lícita y con información veraz.</li>
          <li>Tu acceso como cliente es personal y no debe compartirse con terceros.</li>
          <li>SITCAV puede actualizar productos, precios y disponibilidad sin previo aviso.</li>
          <li>El uso continuo del acceso implica aceptación de estas condiciones.</li>
        </ul>

        <div class="storefront-modal__actions">
          <button type="button" class="btn btn-outline" id="terms-cancel-btn">Cancelar</button>
          <button type="button" class="btn btn-primary" id="terms-accept-btn">Aceptar y continuar</button>
        </div>
      </div>
    </div>

    <script>
      window.ECOMMERCE_AUTH_USER = <?= json_encode($ecommerceAuthenticatedUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

      const API_BASE_URL = '.';
      const GOOGLE_LOGIN_URL = 'oauth2/google';
      let productsData = [];
      let currentCarouselPosition = 0;

      const PLACEHOLDER_IMAGE = 'data:image/svg+xml,' + encodeURIComponent(`
      <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
        <rect width="200" height="200" fill="#e5e7eb"/>
        <text x="100" y="100" font-family="Arial" font-size="14" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">Sin imagen</text>
      </svg>
      `);

      function handleImageError(img) {
        img.onerror = null;
        img.src = PLACEHOLDER_IMAGE;
      }

      function openTermsModal() {
        const modal = document.getElementById('terms-modal');

        if (!modal) {
          window.location.href = GOOGLE_LOGIN_URL;
          return;
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
      }

      function closeTermsModal() {
        const modal = document.getElementById('terms-modal');

        if (!modal) {
          return;
        }

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }

      function renderCarousel() {
        const track = document.getElementById('carousel-track');
        const emptyState = document.getElementById('storefront-empty');

        if (!track || !emptyState) {
          return;
        }

        track.innerHTML = '';

        if (productsData.length === 0) {
          emptyState.hidden = false;
          return;
        }

        emptyState.hidden = true;

        productsData.forEach((product) => {
          const card = document.createElement('article');
          const imgUrl = product.imagen_url
            ? (product.imagen_url.startsWith('http') ? product.imagen_url : `${API_BASE_URL}${product.imagen_url}`)
            : '';

          card.className = 'product-card';
          card.innerHTML = `
            <img
              src="${imgUrl || PLACEHOLDER_IMAGE}"
              alt="${product.nombre}"
              class="product-image"
              onerror="handleImageError(this)">
            <h4>${product.nombre}</h4>
            <p class="product-description">${product.descripcion || 'Sin descripcion disponible.'}</p>
            <div class="product-price">$${parseFloat(product.precio_unitario_actual_dolares || 0).toFixed(2)}</div>
            <div class="product-stock">Stock: ${product.cantidad_disponible ?? 0} unidades</div>
          `;

          track.appendChild(card);
        });
      }

      async function loadProductCarousel() {
        try {
          const response = await fetch(`${API_BASE_URL}/api/productos`);
          productsData = await response.json();
          renderCarousel();
        } catch (error) {
          console.error('Error loading products:', error);
          productsData = [];
          renderCarousel();
        }
      }

      function moveCarousel(direction) {
        const track = document.getElementById('carousel-track');

        if (!track || productsData.length === 0) {
          return;
        }

        const cardWidth = 270;
        const visibleCards = Math.max(1, Math.floor(track.parentElement.offsetWidth / cardWidth));
        const maxPosition = Math.max(0, productsData.length - visibleCards);

        currentCarouselPosition += direction;
        currentCarouselPosition = Math.max(0, Math.min(currentCarouselPosition, maxPosition));

        track.style.transform = `translateX(-${currentCarouselPosition * cardWidth}px)`;
      }

      window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-google-login-trigger="true"]').forEach((link) => {
          link.addEventListener('click', (event) => {
            event.preventDefault();
            openTermsModal();
          });
        });

        document.getElementById('terms-cancel-btn')?.addEventListener('click', () => {
          closeTermsModal();
        });

        document.getElementById('terms-accept-btn')?.addEventListener('click', () => {
          window.location.href = GOOGLE_LOGIN_URL;
        });

        document.getElementById('terms-modal')?.addEventListener('click', (event) => {
          if (event.target.id === 'terms-modal') {
            closeTermsModal();
          }
        });

        loadProductCarousel();
      });

      window.addEventListener('resize', () => {
        moveCarousel(0);
      });
    </script>
  </body>

</html>
