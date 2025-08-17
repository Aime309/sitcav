<script>
  import logo from "@/recursos/logo-texto.png";
</script>

<nav
  class="navbar navbar-expand-lg text-white text-uppercase fs-7 ls-1 py-5 align-items-center"
>
  <div class="container-fluid">
    <div class="row align-items-center w-100">
      <div class="col-8 col-md-3">
        <a class="navbar-brand" href="./">
          <img src={logo.src} height="46" alt="logo" style:filter="invert(1)" />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasNavbar"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="col-1 col-md-6 d-md-flex justify-content-center">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            >
            </button>
          </div>

          <div class="offcanvas-body">
            <ul
              class="navbar-nav justify-content-end flex-grow-1 gap-1 gap-md-5 pe-3"
            >
              <li class="nav-item">
                <a class="nav-link active" href="./">Inicio</a>
              </li>
              <!-- <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  data-bs-toggle="dropdown"
                >
                  Pages
                </a>
                <ul class="dropdown-menu list-unstyled p-4">
                  <li>
                    <a
                      href="shop.html"
                      class="dropdown-item d-flex justify-content-between align-items-center"
                    >
                      Shop
                      <span class="badge bg-primary ms-2">PRO</span>
                    </a>
                  </li>
                </ul>
              </li> -->
              <li class="nav-item">
                <a href="./ingresar" class="btn btn-outline-light rounded-pill">
                  Ingresar
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="./crear-cuenta"
                  class="btn btn-outline-light rounded-pill"
                >
                  Crear cuenta
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-3 col-md-3 text-md-end">
        <ul class="list-unstyled d-flex justify-content-end m-0">
          <!-- <li>
            <a href="wishlist.html" class="mx-2" aria-label="Lista de deseos">
              <svg width="24" height="24" viewBox="0 0 24 24">
                <use xlink:href="#heart"></use>
              </svg>
            </a>
          </li> -->
          <!-- <li>
            <a
              href="#"
              class="mx-2"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasCart"
            >
              <svg width="24" height="24" viewBox="0 0 24 24">
                <use xlink:href="#cart"></use>
              </svg>
            </a>
          </li> -->
          <!-- <li class="mx-2">
            <a href="#search" class="search-button">
              <svg width="24" height="24" viewBox="0 0 24 24">
                <use xlink:href="#search"></use>
              </svg>
            </a>
          </li> -->
        </ul>
      </div>
    </div>
  </div>
</nav>
