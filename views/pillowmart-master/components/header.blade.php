<header class="main_menu home_menu">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light">
          <a class="navbar-brand" href="{{ Flight::getUrl('ecommerce.index') }}">
            <img alt="logo" height="38" src="./img/logo.png" />
          </a>
          <button class="navbar-toggler" data-target="#navbarSupportedContent"
            data-toggle="collapse">
            <span class="menu_icon">
              <i class="fas fa-bars"></i>
            </span>
          </button>
          <div class="navbar-collapse main-menu-item collapse"
            id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="{{ Flight::getUrl('ecommerce.index') }}">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ Flight::getUrl('ecommerce.product_list') }}">Productos</a>
              </li>
              <li class="nav-item d-flex align-items-center">
                @if (auth()->user())
                <img class="rounded-circle" height="38"
                  src="data:image/jpeg;base64,{{ base64_encode(auth()->user()->avatar) }}"
                  width="38" />
                <a class="nav-link pl-2" href="{{ Flight::getUrl('ecommerce.logout') }}">Cerrar sesión</a>
                @else
                <a class="nav-link" href="{{ Flight::getUrl('ecommerce.login') }}">Ingresar</a>
                @endif
              </li>
            </ul>
          </div>
          <div class="hearer_icon d-flex align-items-center">
            <a class="btn position-relative {{ auth()->user() ?: "disabled" }} p-0"
              href="{{ Flight::getUrl('ecommerce.cart') }}">
              <i class="flaticon-shopping-cart-black-shape"></i>
              <span class="badge badge-dark position-absolute right-0 top-0"
                x-text="reservedProducts.length">
              </span>
            </a>
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>
