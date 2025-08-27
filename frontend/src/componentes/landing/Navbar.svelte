<script>
  import logo from "@/recursos/logo-texto.png";
  import "bootstrap/js/dist/offcanvas";

  const offcanvasId = crypto.randomUUID();
</script>

<nav class="navbar navbar-expand-xl text-uppercase py-5">
  <div class="container">
    <a href="./" class="navbar-brand">
      <img src={logo.src} height="46" alt="Logo" style:filter="invert(1)" />
    </a>
    <section class="offcanvas offcanvas-end" tabindex="-1" id={offcanvasId}>
      <header class="offcanvas-header">
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        >
        </button>
      </header>

      <div class="offcanvas-body">
        <ul
          class="navbar-nav justify-content-center align-items-center flex-grow-1 gap-5"
        >
          <li class="nav-item">
            <a href="./" class="nav-link">Inicio</a>
          </li>
          <!-- <li class="nav-item">
            <div class="dropdown">
              <a
                href="#dropdown"
                class="nav-link dropdown-toggle text-center"
                data-bs-toggle="dropdown"
              >
                Pages
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a
                    href="./shop"
                    class="dropdown-item d-flex justify-content-between align-items-center"
                  >
                    Shop
                    <span class="badge text-bg-primary">PRO</span>
                  </a>
                </li>
              </ul>
            </div>
          </li> -->
          <li class="nav-item">
            <a href="./ingresar" class="btn btn-outline-light"> Ingresar </a>
          </li>
          <li class="nav-item">
            <a href="./crear-cuenta" class="btn btn-outline-light">
              Crear cuenta
            </a>
          </li>
        </ul>
      </div>
    </section>
    <ul class="navbar-nav flex-row align-items-center gap-3">
      <!-- <li class="nav-item">
        <a href="wishlist.html" aria-label="Lista de deseos">
          <i class="bi bi-heart"></i>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a
          href="#cart"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasCart"
          aria-label="Mi carrito"
        >
          <i class="bi bi-cart"></i>
        </a>
      </li> -->
      <!-- <li class="nav-item">
        <a href="#search" class="search-button" aria-label="Buscar">
          <i class="bi bi-search"></i>
        </a>
      </li> -->
      <li class="nav-item">
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#{offcanvasId}"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
      </li>
    </ul>
  </div>
</nav>
