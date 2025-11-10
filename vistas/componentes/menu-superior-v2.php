<?php

$idEnlacesMovilOffcanvas = uniqid();
$idModalBusqueda = uniqid();

?>

<header class="topbar">
  <div class="with-vertical">
    <nav class="navbar navbar-expand-lg p-0">
      <ul class="navbar-nav">
        <li
          class="nav-item nav-icon-hover ms-n3 d-xl-none"
          :class="{ 'd-xl-none': noHayNavs }">
          <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <i class="navbar-toggler-icon"></i>
          </a>
        </li>
        <?php # Flight::render('componentes/enlaces-recientes') ?>
      </ul>

      <div class="d-block d-lg-none">
        <img
          :src="`./recursos/imagenes/logo-horizontal-${tema}.png`"
          width="180" />
      </div>
      <?php Flight::render('componentes/menu-superior-v2-enlaces', compact('idEnlacesMovilOffcanvas', 'idModalBusqueda')) ?>
    </nav>
    <div class="offcanvas offcanvas-start pt-0" data-bs-scroll="true" tabindex="-1" id="<?= $idEnlacesMovilOffcanvas ?>" aria-labelledby="offcanvasWithBothOptionsLabel">
      <nav class="sidebar-nav scroll-sidebar">
        <div class="offcanvas-header justify-content-between">
          <a href="./">
            <img :src="`./recursos/imagenes/logo-horizontal-${tema}.png`" class="img-fluid w-75" />
          </a>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body pt-0 h-n80 overflow-auto">
          <ul class="list-unstyled" id="sidebarnav">
            <li class="sidebar-item">
              <a class="sidebar-link has-arrow ms-0" href="javascript:void(0)" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:slider-vertical-line-duotone" class="fs-7"></iconify-icon>
                </span>
                <span class="hide-menu">Apps</span>
              </a>
              <ul aria-expanded="false" class="collapse first-level my-3">
                <li class="sidebar-item py-2">
                  <a href="../main/app-chat.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-chat.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Chat Application</h6>
                      <span class="fs-3 d-block text-muted">New messages arrived</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-invoice.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-invoice.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Invoice App</h6>
                      <span class="fs-3 d-block text-muted">Get latest invoice</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-contact2.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-mobile.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Contact Application</h6>
                      <span class="fs-3 d-block text-muted">2 Unsaved Contacts</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-email.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-message-box.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Email App</h6>
                      <span class="fs-3 d-block text-muted">Get new emails</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/page-user-profile.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-cart.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">User Profile</h6>
                      <span class="fs-3 d-block text-muted">learn more information</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-calendar.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-date.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Calendar App</h6>
                      <span class="fs-3 d-block text-muted">Get dates</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-contact.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-lifebuoy.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Contact List Table</h6>
                      <span class="fs-3 d-block text-muted">Add new contact</span>
                    </div>
                  </a>
                </li>
                <li class="sidebar-item py-2">
                  <a href="../main/app-notes.html" class="d-flex align-items-center">
                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                      <img src="./recursos/images/svgs/icon-dd-application.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                    </div>
                    <div class="d-inline-block">
                      <h6 class="mb-0 bg-hover-primary">Notes Application</h6>
                      <span class="fs-3 d-block text-muted">To-do and Daily tasks</span>
                    </div>
                  </a>
                </li>
                <ul class="px-8 mt-7 mb-4">
                  <li class="sidebar-item mb-3">
                    <h5 class="fs-5 fw-semibold">Quick Links</h5>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/page-pricing.html">Pricing Page</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/authentication-login.html">Authentication
                      Design</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/authentication-register.html">Register
                      Now</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/authentication-error.html">404 Error
                      Page</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/app-notes.html">Notes App</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/page-user-profile.html">User
                      Application</a>
                  </li>
                  <li class="mb-3">
                    <a class="fw-semibold bg-hover-primary" href="../main/page-account-settings.html">Account
                      Settings</a>
                  </li>
                </ul>
              </ul>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link ms-0" href="../main/app-chat.html" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:chat-unread-outline" class="fs-7"></iconify-icon>
                </span>
                <span class="hide-menu">Chat</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link ms-0" href="../main/app-calendar.html" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:calendar-minimalistic-outline" class="fs-7"></iconify-icon>
                </span>
                <span class="hide-menu">Calendar</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link ms-0" href="../main/app-email.html" aria-expanded="false">
                <span>
                  <iconify-icon icon="solar:inbox-unread-outline" class="fs-7"></iconify-icon>
                </span>
                <span class="hide-menu">Email</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
  <div class="app-header with-horizontal">
    <nav class="navbar navbar-expand-xl container-fluid p-0">
      <ul class="navbar-nav">
        <li class="nav-item d-none d-xl-block">
          <a href="./" class="text-nowrap nav-link">
            <img
              :src="`./recursos/imagenes/logo-horizontal-${tema}.png`"
              width="180"
              class="img-fluid" />
          </a>
        </li>
        <?php # Flight::render('componentes/enlaces-recientes') ?>
      </ul>
      <?php Flight::render('componentes/menu-superior-v2-enlaces', compact('idEnlacesMovilOffcanvas', 'idModalBusqueda')) ?>
    </nav>
  </div>
</header>

<div class="modal fade" id="<?= $idModalBusqueda ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
    <div class="modal-content rounded">
      <div class="modal-header border-bottom">
        <input type="search" class="form-control fs-3" placeholder="Search here" id="search" />
        <a href="javascript:void(0)" data-bs-dismiss="modal" class="lh-1">
          <i class="ti ti-x fs-5 ms-3"></i>
        </a>
      </div>
      <div class="modal-body message-body" data-simplebar="">
        <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
        <ul class="list mb-0 py-2">
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Analytics</span>
              <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">eCommerce</span>
              <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">CRM</span>
              <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard3</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Contacts</span>
              <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Posts</span>
              <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Detail</span>
              <span class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Shop</span>
              <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Modern</span>
              <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Dashboard</span>
              <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Contacts</span>
              <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Posts</span>
              <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Detail</span>
              <span class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
            </a>
          </li>
          <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
            <a href="javascript:void(0)">
              <span class="fs-3 text-dark fw-normal d-block">Shop</span>
              <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
