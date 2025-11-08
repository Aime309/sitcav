<?php

$idDeRecursos = $_ENV['ENVIRONMENT'] === 'development' ? uniqid() : '';
$errores = (array) flash()->display('errores');
$exitos = (array) flash()->display('exitos');

?>

<!doctype html>
<html
  dir="ltr"
  data-layout="vertical"
  data-bs-theme="<?= session()->get('tema', 'light') ?>"
  data-color-theme="<?= session()->get('tema_colores', 'Blue_Theme') ?>"
  x-data="tema"
  :data-bs-theme="tema"
  :data-color-theme="tema_colores">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $titulo ?> | SITCAV</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./recursos/imagenes/favicon.png" />
  <link
    rel="stylesheet"
    href="./recursos/compilados/autenticados.css?id=<?= $idDeRecursos ?>" />
  <link rel="stylesheet" href="./recursos/css/materialm.css?id=<?= $idDeRecursos ?>" />
  <script src="./recursos/compilados/autenticados.js?id=<?= $idDeRecursos ?>"></script>
  <!-- Jvectormap  -->
  <link rel="stylesheet" href="./recursos/libs/jvectormap/jquery-jvectormap.css" />
</head>

<body>
  <!-- Toast -->
  <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body hstack align-items-start gap-6">
      <i class="ti ti-alert-circle fs-6"></i>
      <div>
        <h5 class="text-white fs-3 mb-1">Welcome to MaterialM</h5>
        <h6 class="text-white fs-2 mb-0">Easy to costomize the Template!!!</h6>
      </div>
      <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  <!-- Preloader -->
  <div class="preloader">
    <img src="./recursos/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="side-mini-panel with-vertical">
      <!-- ---------------------------------- -->
      <!-- Start Vertical Layout Sidebar -->
      <!-- ---------------------------------- -->
      <div class="iconbar">
        <div>
          <div class="mini-nav">
            <div class="brand-logo d-flex align-items-center justify-content-between justify-content-lg-center">
              <a href="../main/index.html" class="text-nowrap logo-img">
                <img src="./recursos/images/logos/logo-icon.svg" alt="Logo" />
              </a>
              <a href="javascript:void(0)" class="sidebartoggler close-btn ms-auto text-decoration-none fs-5 d-flex d-xl-none align-items-center justify-content-center text-danger">
                <i class="material-symbols-outlined fs-5">cancel</i>
              </a>
            </div>
            <ul class="mini-nav-ul" data-simplebar>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Dashboards -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-1">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Dashboards">
                  <iconify-icon icon="solar:layers-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Landingpage -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-2">
                <a href="../landingpage/index.html" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Landingpage">
                  <iconify-icon icon="solar:widget-add-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Pages -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-3">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Pages">
                  <iconify-icon icon="solar:notes-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Forms  -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-4">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Forms">
                  <iconify-icon icon="solar:palette-round-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>

              <li>
                <span class="sidebar-divider lg"></span>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Tables -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-5">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Tables">
                  <iconify-icon icon="solar:tuning-square-2-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Charts -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-6">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Charts">
                  <iconify-icon icon="solar:chart-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Ui -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-7">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Ui Components">
                  <iconify-icon icon="solar:widget-6-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Components -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-8">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Components">
                  <iconify-icon icon="solar:archive-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Auth -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-9">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Authentication Pages">
                  <iconify-icon icon="solar:lock-keyhole-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <!-- Multi level -->
              <!-- --------------------------------------------------------------------------------------------------------- -->
              <li class="mini-nav-item" id="mini-10">
                <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-placement="right" data-bs-title="Docs &amp; Other">
                  <iconify-icon icon="solar:mirror-left-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
            </ul>
            <ul class="mt-auto mb-4">
              <li class="dropup">
                <a href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="d-flex align-items-center justify-content-center gap-2 lh-base hover-border p-1 mx-auto rounded-circle" width="35">
                    <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="MaterialM-img" />
                  </div>
                </a>
                <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up shadow-lg" aria-labelledby="drop1">
                  <div class="profile-dropdown position-relative" data-simplebar>
                    <div class="py-3 px-7 pb-0">
                      <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                    </div>
                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                      <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="MaterialM-img" />
                      <div class="ms-3">
                        <h5 class="mb-0 fs-4">Jonathan Deo</h5>
                        <span class="mb-1 d-block">Admin</span>
                        <p class="mb-0 d-flex align-items-center gap-2">
                          <i class="ti ti-mail fs-4"></i> info@MaterialM.com
                        </p>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="../main/page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                          <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7"></iconify-icon>
                        </span>
                        <div class="w-75 v-middle ps-3">
                          <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                          <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                        </div>
                      </a>
                      <a href="../main/app-email.html" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                          <iconify-icon icon="solar:inbox-line-duotone" class="fs-7"></iconify-icon>
                        </span>
                        <div class="w-75 v-middle ps-3">
                          <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                          <span class="fs-2 d-block text-body-secondary">Messages & Emails</span>
                        </div>
                      </a>
                      <a href="../main/app-notes.html" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                          <iconify-icon icon="solar:checklist-minimalistic-line-duotone" class="fs-7"></iconify-icon>
                        </span>
                        <div class="w-75 v-middle ps-3">
                          <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                          <span class="fs-2 d-block text-body-secondary">To-do and Daily Tasks</span>
                        </div>
                      </a>
                    </div>
                    <div class="d-grid py-4 px-7 pt-8">
                      <a href="../main/authentication-login.html" class="btn btn-primary">Log Out</a>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="sidebarmenu">
            <!-- ---------------------------------- -->
            <!-- Dashboard -->
            <!-- ---------------------------------- -->
            <nav class="sidebar-nav" id="menu-right-mini-1" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Dashboards</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/index2.html" aria-expanded="false">
                    <iconify-icon icon="solar:widget-add-line-duotone" class=""></iconify-icon>
                    <span class="hide-menu">eCommerce</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="" id="get-url" aria-expanded="false">
                    <iconify-icon icon="solar:chart-line-duotone" class=""></iconify-icon>
                    <span class="hide-menu">Analytics</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/index3.html" aria-expanded="false">
                    <iconify-icon icon="solar:layers-line-duotone" class=""></iconify-icon>
                    <span class="hide-menu">CRM</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <iconify-icon icon="solar:home-angle-line-duotone"></iconify-icon>
                    <span class="hide-menu">Front Pages</span>
                  </a>
                  <ul aria-expanded="false" class="collapse first-level">
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-landingpage.html">
                        <span class="icon-small"></span>
                        Homepage
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-aboutpage.html">
                        <span class="icon-small"></span>
                        About Us
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-blogpage.html">
                        <span class="icon-small"></span>
                        Blog
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-blogdetailpage.html">
                        <span class="icon-small"></span>
                        Blog Details
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-contactpage.html">
                        <span class="icon-small"></span>
                        Contact Us
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-portfoliopage.html">
                        <span class="icon-small"></span>
                        Portfolio
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/frontend-pricingpage.html">
                        <span class="icon-small"></span>
                        Pricing
                      </a>
                    </li>
                  </ul>
                </li>

                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Apps</span>
                </li>

                <li class="sidebar-item">
                  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <iconify-icon icon="solar:cart-3-line-duotone" class=""></iconify-icon>
                    <span class="hide-menu">Ecommerce</span>
                  </a>
                  <ul aria-expanded="false" class="collapse first-level">
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-shop.html">
                        <span class="icon-small"></span> Shop
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-shop-detail.html">
                        <span class="icon-small"></span>Details
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-product-list.html">
                        <span class="icon-small"></span>List
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-checkout.html">
                        <span class="icon-small"></span>Checkout
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-add-product.html">
                        <span class="icon-small"></span>Add Product
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/eco-edit-product.html">
                        <span class="icon-small"></span>Edit Product
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                    <span class="hide-menu">Blog</span>
                  </a>
                  <ul aria-expanded="false" class="collapse first-level">
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/blog-posts.html">
                        <span class="icon-small"></span>Blog
                        Posts
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link" href="../main/blog-detail.html">
                        <span class="icon-small"></span>Blog
                        Details
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/page-user-profile.html" aria-expanded="false">
                    <iconify-icon icon="solar:shield-user-line-duotone"></iconify-icon>
                    User Profile
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-email.html"><iconify-icon icon="solar:letter-line-duotone"></iconify-icon>Email</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-calendar.html"><iconify-icon icon="solar:calendar-mark-line-duotone"></iconify-icon>Calendar</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-kanban.html"><iconify-icon icon="solar:airbuds-case-minimalistic-line-duotone"></iconify-icon>Kanban</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-chat.html"><iconify-icon icon="solar:chat-round-line-line-duotone"></iconify-icon>Chat</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-notes.html"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon>Notes</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-contact.html"><iconify-icon icon="solar:iphone-line-duotone"></iconify-icon>Contact Table</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-contact2.html"><iconify-icon icon="solar:phone-line-duotone"></iconify-icon>Contact List</a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/app-invoice.html"><iconify-icon icon="solar:bill-list-line-duotone"></iconify-icon>Invoice</a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav" id="menu-right-mini-2" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">LandingPage</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../landingpage/index.html"><iconify-icon icon="solar:bill-list-line-duotone"></iconify-icon> Landingpage</a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-3" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Pages</span>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-animation.html" class="sidebar-link">
                    <iconify-icon icon="solar:accessibility-line-duotone"></iconify-icon>
                    <span class="hide-menu">Animation</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-search-result.html" class="sidebar-link">
                    <iconify-icon icon="solar:card-search-line-duotone"></iconify-icon>
                    <span class="hide-menu">Search Result</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-gallery.html" class="sidebar-link">
                    <iconify-icon icon="solar:gallery-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Gallery</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-treeview.html" class="sidebar-link">
                    <iconify-icon icon="solar:mask-happly-line-duotone"></iconify-icon>
                    <span class="hide-menu">Treeview</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-block-ui.html" class="sidebar-link">
                    <iconify-icon icon="solar:quit-full-screen-square-line-duotone"></iconify-icon>
                    <span class="hide-menu">Block-Ui</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/pages-session-timeout.html" class="sidebar-link">
                    <iconify-icon icon="solar:sort-by-time-line-duotone"></iconify-icon>
                    <span class="hide-menu">Session Timeout</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/page-pricing.html" class="sidebar-link">
                    <iconify-icon icon="solar:dollar-line-duotone"></iconify-icon>
                    <span class="hide-menu">Pricing</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/page-faq.html" class="sidebar-link">
                    <iconify-icon icon="solar:question-circle-line-duotone"></iconify-icon>
                    <span class="hide-menu">FAQ</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/page-account-settings.html" class="sidebar-link">
                    <iconify-icon icon="solar:settings-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Account Setting</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/starter.html" class="sidebar-link">
                    <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
                    <span class="hide-menu">Starter</span>
                  </a>
                </li>
                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Icons</span>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link sidebar-link" href="../main/icon-tabler.html" aria-expanded="false">
                    <iconify-icon icon="solar:sticker-smile-circle-2-line-duotone"></iconify-icon>
                    <span class="hide-menu">Tabler Icon</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link sidebar-link" href="../main/icon-solar.html" aria-expanded="false">
                    <iconify-icon icon="solar:sticker-smile-circle-2-line-duotone"></iconify-icon>
                    <span class="hide-menu">Solar Icon</span>
                  </a>
                </li>
                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Widgets</span>
                </li>

                <!-- ---------------------------------- -->
                <!-- Widgets -->
                <!-- ---------------------------------- -->
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-cards.html">
                    <iconify-icon icon="solar:cardholder-line-duotone"></iconify-icon>
                    <span class="hide-menu">Cards</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-banners.html">
                    <iconify-icon icon="solar:align-vertical-spacing-line-duotone"></iconify-icon>
                    <span class="hide-menu">Banner</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-charts.html">
                    <iconify-icon icon="solar:chart-square-line-duotone"></iconify-icon>
                    <span class="hide-menu">Charts</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-feeds.html">
                    <iconify-icon icon="solar:feed-line-duotone"></iconify-icon>
                    <span class="hide-menu">Feeds</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-apps.html">
                    <iconify-icon icon="solar:clapperboard-text-line-duotone"></iconify-icon>
                    <span class="hide-menu">Apps</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="../main/widgets-data.html">
                    <iconify-icon icon="solar:database-line-duotone"></iconify-icon>
                    <span class="hide-menu">Data</span>
                  </a>
                </li>

              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-4" data-simplebar>
              <div>
                <ul class="sidebar-menu" id="sidebarnav">
                  <!-- ---------------------------------- -->
                  <!-- Home -->
                  <!-- ---------------------------------- -->
                  <li class="nav-small-cap">
                    <span class="hide-menu">Forms</span>
                  </li>
                  <!-- ---------------------------------- -->
                  <!-- Form elements -->
                  <!-- ---------------------------------- -->
                  <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                      <iconify-icon icon="solar:text-selection-line-duotone"></iconify-icon>
                      <span class="hide-menu">Forms Elements</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                      <li class="sidebar-item">
                        <a href="../main/form-inputs.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Forms Input</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-input-groups.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Input Groups</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-input-grid.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Input Grid</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-checkbox-radio.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Checkbox & Radios</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-bootstrap-switch.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">BT Switch</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-select2.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Select2</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!-- ---------------------------------- -->
                  <!-- Form Input -->
                  <!-- ---------------------------------- -->
                  <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                      <iconify-icon icon="solar:password-minimalistic-input-line-duotone"></iconify-icon>
                      <span class="hide-menu">Forms Inputs</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                      <li class="sidebar-item">
                        <a href="../main/form-basic.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Basic Form</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-vertical.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Vertical</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-horizontal.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Horizontal</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-actions.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Actions</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-row-separator.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Row Separator</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-bordered.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Bordered</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-detail.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Detail</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-striped-row.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Striped Rows</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="../main/form-floating-input.html" class="sidebar-link">
                          <span class="icon-small"></span>
                          <span class="hide-menu">Form Floating Input</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-wizard.html" class="sidebar-link">
                      <iconify-icon icon="solar:archive-line-duotone"></iconify-icon>
                      <span class="hide-menu">Form Wizard</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-repeater.html" class="sidebar-link">
                      <iconify-icon icon="solar:repeat-one-minimalistic-bold-duotone"></iconify-icon>
                      <span class="hide-menu">Form Repeater</span>
                    </a>
                  </li>
                  <li>
                    <span class="sidebar-divider lg"></span>
                  </li>
                  <li class="nav-small-cap">
                    <span class="hide-menu">Addons</span>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-dropzone.html" class="sidebar-link">
                      <iconify-icon icon="solar:flip-horizontal-line-duotone"></iconify-icon>
                      <span class="hide-menu">Dropzone</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-mask.html" class="sidebar-link">
                      <iconify-icon icon="solar:mask-happly-line-duotone"></iconify-icon>
                      <span class="hide-menu">Form Mask</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-typeahead.html" class="sidebar-link">
                      <iconify-icon icon="solar:high-quality-line-duotone"></iconify-icon>
                      <span class="hide-menu">Form Typehead</span>
                    </a>
                  </li>
                  <li>
                    <span class="sidebar-divider lg"></span>
                  </li>
                  <li class="nav-small-cap">
                    <span class="hide-menu">Validation</span>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-bootstrap-validation.html" class="sidebar-link">
                      <iconify-icon icon="solar:shield-warning-line-duotone"></iconify-icon>
                      <span class="hide-menu">Bootstrap Validation</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-custom-validation.html" class="sidebar-link">
                      <iconify-icon icon="solar:shield-warning-line-duotone"></iconify-icon>
                      <span class="hide-menu">Custom Validation</span>
                    </a>
                  </li>
                  <li>
                    <span class="sidebar-divider lg"></span>
                  </li>
                  <li class="nav-small-cap">
                    <span class="hide-menu">Pickers</span>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-picker-colorpicker.html" class="sidebar-link">
                      <iconify-icon icon="solar:waterdrop-line-duotone"></iconify-icon>
                      <span class="hide-menu">Colorpicker</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-picker-bootstrap-rangepicker.html" class="sidebar-link">
                      <iconify-icon icon="solar:square-transfer-horizontal-line-duotone"></iconify-icon>
                      <span class="hide-menu">Rangepicker</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-picker-bootstrap-datepicker.html" class="sidebar-link">
                      <iconify-icon icon="solar:calendar-date-line-duotone"></iconify-icon>
                      <span class="hide-menu">BT Datepicker</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-picker-material-datepicker.html" class="sidebar-link">
                      <iconify-icon icon="solar:smartphone-update-line-duotone"></iconify-icon>
                      <span class="hide-menu">MT Datepicker</span>
                    </a>
                  </li>
                  <li>
                    <span class="sidebar-divider lg"></span>
                  </li>
                  <li class="nav-small-cap">
                    <span class="hide-menu">Editors</span>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-editor-quill.html" class="sidebar-link">
                      <iconify-icon icon="solar:clapperboard-edit-line-duotone"></iconify-icon>
                      <span class="hide-menu">Quill Editor</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-editor-tinymce.html" class="sidebar-link">
                      <iconify-icon icon="solar:clapperboard-edit-line-duotone"></iconify-icon>
                      <span class="hide-menu">Tinymce Edtor</span>
                    </a>
                  </li>
                </ul>
              </div>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-5" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Bootstrap Tables</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->

                <li class="sidebar-item">
                  <a href="../main/table-basic.html" class="sidebar-link">
                    <iconify-icon icon="solar:tablet-line-duotone"></iconify-icon>
                    <span class="hide-menu">Basic Table</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-dark-basic.html" class="sidebar-link">
                    <iconify-icon icon="solar:tablet-line-duotone"></iconify-icon>
                    <span class="hide-menu">Dark Table</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-sizing.html" class="sidebar-link">
                    <iconify-icon icon="solar:tablet-line-duotone"></iconify-icon>
                    <span class="hide-menu">Sizing Table</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-layout-coloured.html" class="sidebar-link">
                    <iconify-icon icon="solar:tablet-line-duotone"></iconify-icon>
                    <span class="hide-menu">Coloured Table</span>
                  </a>
                </li>
                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Datatables</span>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-datatable-basic.html" class="sidebar-link">
                    <iconify-icon icon="solar:align-horizonta-spacing-line-duotone"></iconify-icon>
                    <span class="hide-menu">Basic</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-datatable-api.html" class="sidebar-link">
                    <iconify-icon icon="solar:align-horizonta-spacing-line-duotone"></iconify-icon>
                    <span class="hide-menu">API</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/table-datatable-advanced.html" class="sidebar-link">
                    <iconify-icon icon="solar:align-horizonta-spacing-line-duotone"></iconify-icon>
                    <span class="hide-menu">Advanced</span>
                  </a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-6" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Charts</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->

                <li class="sidebar-item">
                  <a href="../main/chart-apex-line.html" class="sidebar-link">
                    <iconify-icon icon="solar:chart-square-line-duotone"></iconify-icon>
                    <span class="hide-menu">Line Chart</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/chart-apex-area.html" class="sidebar-link">
                    <iconify-icon icon="solar:pie-chart-3-line-duotone"></iconify-icon>
                    <span class="hide-menu">Area Chart</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/chart-apex-bar.html" class="sidebar-link">
                    <iconify-icon icon="solar:chart-2-line-duotone"></iconify-icon>
                    <span class="hide-menu">Bar Chart</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/chart-apex-pie.html" class="sidebar-link">
                    <iconify-icon icon="solar:pie-chart-line-duotone"></iconify-icon>
                    <span class="hide-menu">Pie Chart</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/chart-apex-radial.html" class="sidebar-link">
                    <iconify-icon icon="solar:chart-square-line-duotone"></iconify-icon>
                    <span class="hide-menu">Radial Chart</span>
                  </a>
                </li>

                <li class="sidebar-item">
                  <a href="../main/chart-apex-radar.html" class="sidebar-link">
                    <iconify-icon icon="solar:round-graph-line-duotone"></iconify-icon>
                    <span class="hide-menu">Radar Chart</span>
                  </a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-7" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Ui</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->

                <li class="sidebar-item">
                  <a href="../main/ui-accordian.html" class="sidebar-link">
                    <iconify-icon icon="solar:waterdrops-line-duotone"></iconify-icon>
                    <span class="hide-menu">Accordian</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-badge.html" class="sidebar-link">
                    <iconify-icon icon="solar:tag-horizontal-line-duotone"></iconify-icon>
                    <span class="hide-menu">Badge</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-buttons.html" class="sidebar-link">
                    <iconify-icon icon="solar:airbuds-case-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Buttons</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-dropdowns.html" class="sidebar-link">
                    <iconify-icon icon="solar:airbuds-case-line-duotone"></iconify-icon>
                    <span class="hide-menu">Dropdowns</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-modals.html" class="sidebar-link">
                    <iconify-icon icon="solar:bolt-line-duotone"></iconify-icon>
                    <span class="hide-menu">Modals</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-tab.html" class="sidebar-link">
                    <iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Tab</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-tooltip-popover.html" class="sidebar-link">
                    <iconify-icon icon="solar:feed-line-duotone"></iconify-icon>
                    <span class="hide-menu">Tooltip & Popover</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-notification.html" class="sidebar-link">
                    <iconify-icon icon="solar:flag-line-duotone"></iconify-icon>
                    <span class="hide-menu">Notification</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-progressbar.html" class="sidebar-link">
                    <iconify-icon icon="solar:programming-line-duotone"></iconify-icon>
                    <span class="hide-menu">Progressbar</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-pagination.html" class="sidebar-link">
                    <iconify-icon icon="solar:waterdrops-line-duotone"></iconify-icon>
                    <span class="hide-menu">Pagination</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-typography.html" class="sidebar-link">
                    <iconify-icon icon="solar:text-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Typography</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-bootstrap-ui.html" class="sidebar-link">
                    <iconify-icon icon="solar:balloon-line-duotone"></iconify-icon>
                    <span class="hide-menu">Bootstrap UI</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-breadcrumb.html" class="sidebar-link">
                    <iconify-icon icon="solar:slider-minimalistic-horizontal-line-duotone"></iconify-icon>
                    <span class="hide-menu">Breadcrumb</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-offcanvas.html" class="sidebar-link">
                    <iconify-icon icon="solar:laptop-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Offcanvas</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-lists.html" class="sidebar-link">
                    <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Lists</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-grid.html" class="sidebar-link">
                    <iconify-icon icon="solar:layers-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Grid</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-carousel.html" class="sidebar-link">
                    <iconify-icon icon="solar:align-horizonta-spacing-line-duotone"></iconify-icon>
                    <span class="hide-menu">Carousel</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-scrollspy.html" class="sidebar-link">
                    <iconify-icon icon="solar:multiple-forward-right-line-duotone"></iconify-icon>
                    <span class="hide-menu">Scrollspy</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-spinner.html" class="sidebar-link">
                    <iconify-icon icon="solar:soundwave-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Spinner</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-link.html" class="sidebar-link">
                    <iconify-icon icon="solar:link-round-angle-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Link</span>
                  </a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-8" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Components</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                <li class="sidebar-item">
                  <a href="../main/component-sweetalert.html" class="sidebar-link">
                    <iconify-icon icon="solar:star-fall-minimalistic-2-line-duotone"></iconify-icon>
                    <span class="hide-menu">Sweet Alert</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/component-nestable.html" class="sidebar-link">
                    <iconify-icon icon="solar:speaker-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Nestable</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/component-noui-slider.html" class="sidebar-link">
                    <iconify-icon icon="solar:watch-square-minimalistic-charge-line-duotone"></iconify-icon>
                    <span class="hide-menu">Noui slider</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/component-rating.html" class="sidebar-link">
                    <iconify-icon icon="solar:stars-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Rating</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/component-toastr.html" class="sidebar-link">
                    <iconify-icon icon="solar:station-minimalistic-bold-duotone"></iconify-icon>
                    <span class="hide-menu">Toastr</span>
                  </a>
                </li>
                <li>
                  <span class="sidebar-divider lg"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Cards</span>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-cards.html" class="sidebar-link">
                    <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Basic Cards</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-card-customs.html" class="sidebar-link">
                    <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Custom Cards</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-card-weather.html" class="sidebar-link">
                    <iconify-icon icon="solar:cloud-snowfall-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Weather Cards</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/ui-card-draggable.html" class="sidebar-link">
                    <iconify-icon icon="solar:password-minimalistic-input-line-duotone"></iconify-icon>
                    <span class="hide-menu">Draggable Cards</span>
                  </a>
                </li>
              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-9" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                  <span class="hide-menu">Auth</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->

                <li class="sidebar-item">
                  <a href="../main/authentication-error.html" class="sidebar-link">
                    <iconify-icon icon="solar:bug-minimalistic-line-duotone"></iconify-icon>
                    <span class="hide-menu">Error</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-login.html" class="sidebar-link">
                    <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                    <span class="hide-menu">Side Login</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-login2.html" class="sidebar-link">
                    <iconify-icon icon="solar:login-3-line-duotone"></iconify-icon>
                    <span class="hide-menu">Boxed Login</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-register.html" class="sidebar-link">
                    <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                    <span class="hide-menu">Side Register</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-register2.html" class="sidebar-link">
                    <iconify-icon icon="solar:user-plus-rounded-line-duotone"></iconify-icon>
                    <span class="hide-menu">Boxed Register</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-forgot-password.html" class="sidebar-link">
                    <iconify-icon icon="solar:password-outline"></iconify-icon>
                    <span class="hide-menu">Side Forgot Pwd</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-forgot-password2.html" class="sidebar-link">
                    <iconify-icon icon="solar:password-outline"></iconify-icon>
                    <span class="hide-menu">Boxed Forgot Pwd</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-two-steps.html" class="sidebar-link">
                    <iconify-icon icon="solar:siderbar-line-duotone"></iconify-icon>
                    <span class="hide-menu">Side Two Steps</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-two-steps2.html" class="sidebar-link">
                    <iconify-icon icon="solar:siderbar-line-duotone"></iconify-icon>
                    <span class="hide-menu">Boxed Two Steps</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a href="../main/authentication-maintenance.html" class="sidebar-link">
                    <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                    <span class="hide-menu">Maintenance</span>
                  </a>
                </li>

              </ul>
            </nav>
            <!-- sidebar -->
            <nav class="sidebar-nav scroll-sidebar" id="menu-right-mini-10" data-simplebar>
              <ul class="sidebar-menu" id="sidebarnav">
                <li class="nav-small-cap">
                  <span class="hide-menu">Documentation</span>
                </li>
                <li class="sidebar-item">
                  <a href="../docs/index.html" class="sidebar-link">
                    <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                    <span class="hide-menu">Getting Started</span>
                  </a>
                </li>
                <li>
                  <span class="sidebar-divider"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">Multi level</span>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)" aria-expanded="false">
                    <iconify-icon icon="solar:align-left-line-duotone"></iconify-icon>
                    <span class="hide-menu">Menu Level</span>
                  </a>
                  <ul aria-expanded="false" class="collapse first-level">
                    <li class="sidebar-item">
                      <a href="javascript:void(0)" class="sidebar-link">
                        <span class="icon-small"></span>
                        <span class="hide-menu">Level 1</span>
                      </a>
                    </li>
                    <li class="sidebar-item">
                      <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="icon-small"></span>
                        <span class="hide-menu">Level 1.1</span>
                      </a>
                      <ul aria-expanded="false" class="collapse two-level">
                        <li class="sidebar-item">
                          <a href="javascript:void(0)" class="sidebar-link">
                            <span class="icon-small"></span>
                            <span class="hide-menu">Level 2</span>
                          </a>
                        </li>
                        <li class="sidebar-item">
                          <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="icon-small"></span>
                            <span class="hide-menu">Level 2.1</span>
                          </a>
                          <ul aria-expanded="false" class="collapse three-level">
                            <li class="sidebar-item">
                              <a href="javascript:void(0)" class="sidebar-link">
                                <span class="icon-small"></span>
                                <span class="hide-menu">Level 3</span>
                              </a>
                            </li>
                            <li class="sidebar-item">
                              <a href="javascript:void(0)" class="sidebar-link">
                                <span class="icon-small"></span>
                                <span class="hide-menu">Level 3.1</span>
                              </a>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li>
                  <span class="sidebar-divider"></span>
                </li>
                <li class="nav-small-cap">
                  <span class="hide-menu">More Options</span>
                </li>
                <li class="sidebar-item">
                  <div class="sidebar-link">
                    <span class="circle-shape bg-primary rounded-pill"></span>
                    <span class="hide-menu">Applications</span>
                  </div>
                </li>
                <li class="sidebar-item">
                  <div class="sidebar-link">
                    <span class="circle-shape bg-secondary rounded-pill"></span>
                    <span class="hide-menu">Form Options</span>
                  </div>
                </li>
                <li class="sidebar-item">
                  <div class="sidebar-link">
                    <span class="circle-shape bg-danger rounded-pill"></span>
                    <span class="hide-menu">Table Variations</span>
                  </div>
                </li>
                <li class="sidebar-item">
                  <div class="sidebar-link">
                    <span class="circle-shape bg-warning rounded-pill"></span>
                    <span class="hide-menu">Charts Selection</span>
                  </div>
                </li>
                <li class="sidebar-item">
                  <div class="sidebar-link">
                    <span class="circle-shape bg-success rounded-pill"></span>
                    <span class="hide-menu">Widgets</span>
                  </div>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </aside>
    <!--  Sidebar End -->
    <div class="page-wrapper">
      <!--  Header Start -->
      <header class="topbar">
        <div class="with-vertical"><!-- ---------------------------------- -->
          <!-- Start Vertical Layout Header -->
          <!-- ---------------------------------- -->
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover ms-n3">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <li class="nav-item nav-icon-hover d-none d-lg-block dropdown">
                <div class="hover-dd">
                  <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false">
                    <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                  </a>
                  <div class="dropdown-menu dropdown-menu-nav  dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="row">
                      <div class="col-8">
                        <div class="ps-3 pt-3">
                          <div class="border-bottom">
                            <div class="row">
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-chat.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Chat Application</h6>
                                      <span class="fs-2 d-block text-muted">New messages arrived</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-invoice.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Invoice App</h6>
                                      <span class="fs-2 d-block text-muted">Get latest invoice</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-mobile.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Contact Application</h6>
                                      <span class="fs-2 d-block text-muted">2 Unsaved Contacts</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-email.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-message-box.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Email App</h6>
                                      <span class="fs-2 d-block text-muted">Get new emails</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-cart.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">User Profile</h6>
                                      <span class="fs-2 d-block text-muted">learn more information</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-date.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Calendar App</h6>
                                      <span class="fs-2 d-block text-muted">Get dates</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-lifebuoy.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Contact List Table</h6>
                                      <span class="fs-2 d-block text-muted">Add new contact</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-notes.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-application.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Notes Application</h6>
                                      <span class="fs-2 d-block text-muted">To-do and Daily tasks</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row align-items-center py-3">
                            <div class="col-8">
                              <a class="fw-semibold text-dark d-flex align-items-center lh-1 bg-hover-primary" href="../main/page-faq.html">
                                <i class="ti ti-help fs-6 me-2"></i>Frequently Asked Questions
                              </a>
                            </div>
                            <div class="col-4">
                              <div class="d-flex justify-content-end pe-4">
                                <button class="btn btn-primary rounded-pill">Check</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-4 ms-n4">
                        <div class="position-relative p-3 border-start h-100">
                          <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>
                          <ul>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-pricing.html">Pricing Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-login.html">Authentication
                                Design</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-register.html">Register Now</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-error.html">404 Error Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/app-notes.html">Notes App</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-user-profile.html">User Application</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-account-settings.html">Account Settings</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>

            <div class="d-block d-lg-none">
              <img src="./recursos/images/logos/dark-logo.svg" class="dark-logo" width="180" alt="MaterialM-img" />
              <img src="./recursos/images/logos/light-logo.svg" class="light-logo" width="180" alt="MaterialM-img" />
            </div>
            <a class="navbar-toggler p-0 border-0 nav-icon-hover" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
              </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                  <li class="nav-item nav-icon-hover dropdown">
                    <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                      <iconify-icon icon="solar:sort-line-duotone" class="fs-7"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover d-none d-xl-block">
                    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover">
                    <a class="nav-link moon dark-layout" href="javascript:void(0)">
                      <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                    </a>
                    <a class="nav-link sun light-layout" href="javascript:void(0)">
                      <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover d-block d-xl-none">
                    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                    </a>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- start message Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover dropdown">
                    <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                      <iconify-icon icon="solar:inbox-line-line-duotone" class="fs-6"></iconify-icon>
                      <span class="badge text-bg-primary fs-1 notification">3</span>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Inbox</h5>
                        <span class="badge text-bg-warning rounded-4 px-3 py-1 lh-sm">3 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-6.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-danger border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Michell Flintoff</h6>
                              <span class="fs-2 d-block">just now</span>
                            </div>
                            <span class="d-block w-100 text-truncate">You: Yesterdy was great...</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-2.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-primary border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Bianca Anderson</h6>
                              <span class="fs-2 d-block">5 mins ago</span>
                            </div>

                            <span class="d-block w-100 text-truncate">Nice looking dress you...</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-3.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-success border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Andrew Johnson</h6>
                              <span class="fs-2 d-block">10 mins ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">Sent a photo</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-4.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-warning border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Marry Strokes</h6>
                              <span class="fs-2 d-block">days ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">
                              If I don’t like something, I’ll stay away from it.
                            </span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-5.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-success border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Josh Anderson</h6>
                              <span class="fs-2 d-block">year ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">$230 deducted from account</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100">See All Messages</button>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end message Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start notification Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover dropdown">
                    <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                      <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                      <div class="notification text-bg-danger rounded-circle fs-1">5</div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                            <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                              <span class="d-block fs-2">9:30 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just see the my new admin!</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Event today</h6>
                              <span class="d-block fs-2">9:15 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just a reminder that you have event</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Settings</h6>
                              <span class="d-block fs-2">4:36 PM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">You can customize this template as you want</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                            <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                              <span class="d-block fs-2">9:30 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just see the my new admin!</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Event today</h6>
                              <span class="d-block fs-2">9:15 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just a reminder that you have event</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Settings</h6>
                              <span class="d-block fs-2">4:36 PM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">You can customize this template as you want</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100">See All Notifications</button>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end notification Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start profile Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                      <div class="d-flex align-items-center gap-2 lh-base">
                        <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="MaterialM-img" />
                      </div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                      <div class="profile-dropdown position-relative" data-simplebar>
                        <div class="py-3 px-7 pb-0">
                          <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                        </div>
                        <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                          <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="MaterialM-img" />
                          <div class="ms-3">
                            <h5 class="mb-0 fs-4">Jonathan Deo</h5>
                            <span class="mb-1 d-block">Admin</span>
                            <p class="mb-0 d-flex align-items-center gap-2">
                              <i class="ti ti-mail fs-4"></i> info@MaterialM.com
                            </p>
                          </div>
                        </div>
                        <div class="message-body">
                          <a href="../main/page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                              <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                              <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                            </div>
                          </a>
                          <a href="../main/app-email.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                              <iconify-icon icon="solar:inbox-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                              <span class="fs-2 d-block text-body-secondary">Messages & Emails</span>
                            </div>
                          </a>
                          <a href="../main/app-notes.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                              <iconify-icon icon="solar:checklist-minimalistic-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                              <span class="fs-2 d-block text-body-secondary">To-do and Daily Tasks</span>
                            </div>
                          </a>
                        </div>
                        <div class="d-grid py-4 px-7 pt-8">
                          <a href="../main/authentication-login.html" class="btn btn-primary">Log Out</a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end profile Dropdown -->
                  <!-- ------------------------------- -->
                </ul>
              </div>
            </div>
          </nav>
          <!-- ---------------------------------- -->
          <!-- End Vertical Layout Header -->
          <!-- ---------------------------------- -->

          <!-- ------------------------------- -->
          <!-- apps Dropdown in Small screen -->
          <!-- ------------------------------- -->
          <!--  Mobilenavbar -->
          <div class="offcanvas offcanvas-start pt-0" data-bs-scroll="true" tabindex="-1" id="mobilenavbar" aria-labelledby="offcanvasWithBothOptionsLabel">
            <nav class="sidebar-nav scroll-sidebar">
              <div class="offcanvas-header justify-content-between">
                <a href="../main/index.html" class="text-nowrap logo-img">
                  <img src="./recursos/images/logos/logo-icon.svg" alt="Logo" />
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body pt-0 h-n80" data-simplebar="" data-simplebar>
                <ul id="sidebarnav">
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
              <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler ms-n3" id="sidebarCollapse" href="javascript:void(0)">
                  <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                </a>
              </li>
              <li class="nav-item d-none d-xl-block">
                <a href=".." class="text-nowrap nav-link">
                  <img src="./recursos/images/logos/dark-logo.svg" class="dark-logo" width="180" alt="MaterialM-img" />
                  <img src="./recursos/images/logos/light-logo.svg" class="light-logo" width="180" alt="MaterialM-img" />
                </a>
              </li>
              <li class="nav-item nav-icon-hover d-none d-lg-block dropdown">
                <div class="hover-dd">
                  <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false">
                    <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                  </a>
                  <div class="dropdown-menu dropdown-menu-nav  dropdown-menu-animate-up" aria-labelledby="drop2">
                    <div class="row">
                      <div class="col-8">
                        <div class="ps-3 pt-3">
                          <div class="border-bottom">
                            <div class="row">
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-chat.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Chat Application</h6>
                                      <span class="fs-2 d-block text-muted">New messages arrived</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-invoice.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Invoice App</h6>
                                      <span class="fs-2 d-block text-muted">Get latest invoice</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-mobile.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Contact Application</h6>
                                      <span class="fs-2 d-block text-muted">2 Unsaved Contacts</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-email.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-message-box.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Email App</h6>
                                      <span class="fs-2 d-block text-muted">Get new emails</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="position-relative">
                                  <a href="../main/page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-cart.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">User Profile</h6>
                                      <span class="fs-2 d-block text-muted">learn more information</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-date.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Calendar App</h6>
                                      <span class="fs-2 d-block text-muted">Get dates</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-lifebuoy.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Contact List Table</h6>
                                      <span class="fs-2 d-block text-muted">Add new contact</span>
                                    </div>
                                  </a>
                                  <a href="../main/app-notes.html" class="d-flex align-items-center pb-9 position-relative">
                                    <div class="text-bg-light rounded-circle round-40 me-3 p-6 d-flex align-items-center justify-content-center">
                                      <img src="./recursos/images/svgs/icon-dd-application.svg" alt="MaterialM-img" class="img-fluid" width="24" height="24" />
                                    </div>
                                    <div class="d-inline-block">
                                      <h6 class="mb-1">Notes Application</h6>
                                      <span class="fs-2 d-block text-muted">To-do and Daily tasks</span>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row align-items-center py-3">
                            <div class="col-8">
                              <a class="fw-semibold text-dark d-flex align-items-center lh-1 bg-hover-primary" href="../main/page-faq.html">
                                <i class="ti ti-help fs-6 me-2"></i>Frequently Asked Questions
                              </a>
                            </div>
                            <div class="col-4">
                              <div class="d-flex justify-content-end pe-4">
                                <button class="btn btn-primary rounded-pill">Check</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-4 ms-n4">
                        <div class="position-relative p-3 border-start h-100">
                          <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>
                          <ul>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-pricing.html">Pricing Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-login.html">Authentication
                                Design</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-register.html">Register Now</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/authentication-error.html">404 Error Page</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/app-notes.html">Notes App</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-user-profile.html">User Application</a>
                            </li>
                            <li class="mb-3">
                              <a class="fw-semibold bg-hover-primary" href="../main/page-account-settings.html">Account Settings</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>

            </ul>
            <div class="d-block d-xl-none">
              <a href=".." class="text-nowrap nav-link">
                <img src="./recursos/images/logos/dark-logo.svg" width="180" alt="MaterialM-img" />
              </a>
            </div>
            <a class="navbar-toggler nav-icon-hover p-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
              </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                  <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                      <iconify-icon icon="solar:sort-line-duotone" class="fs-7"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover d-none d-lg-block">
                    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover">
                    <a class="nav-link moon dark-layout" href="javascript:void(0)">
                      <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                    </a>
                    <a class="nav-link sun light-layout" href="javascript:void(0)">
                      <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                    </a>
                  </li>
                  <li class="nav-item nav-icon-hover d-block d-xl-none">
                    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                    </a>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- start message Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover dropdown">
                    <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                      <iconify-icon icon="solar:inbox-line-line-duotone" class="fs-6"></iconify-icon>
                      <span class="badge text-bg-primary fs-1 notification">3</span>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Inbox</h5>
                        <span class="badge text-bg-warning rounded-4 px-3 py-1 lh-sm">3 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-6.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-danger border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Michell Flintoff</h6>
                              <span class="fs-2 d-block">just now</span>
                            </div>
                            <span class="d-block w-100 text-truncate">You: Yesterdy was great...</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-2.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-primary border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Bianca Anderson</h6>
                              <span class="fs-2 d-block">5 mins ago</span>
                            </div>

                            <span class="d-block w-100 text-truncate">Nice looking dress you...</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-3.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-success border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Andrew Johnson</h6>
                              <span class="fs-2 d-block">10 mins ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">Sent a photo</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-4.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-warning border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Marry Strokes</h6>
                              <span class="fs-2 d-block">days ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">
                              If I don’t like something, I’ll stay away from it.
                            </span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3 position-relative">
                            <img src="./recursos/images/profile/user-5.jpg" alt="user" class="rounded-circle" width="45" height="45" />
                            <span class="position-absolute top-25 start-75 translate-middle-x p-1 bg-success border border-light rounded-circle">
                              <span class="visually-hidden">New alerts</span>
                            </span>
                          </span>
                          <div class="w-75 v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1">Josh Anderson</h6>
                              <span class="fs-2 d-block">year ago</span>
                            </div>
                            <span class="d-block w-100 text-truncate">$230 deducted from account</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100">See All Messages</button>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end message Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start notification Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item nav-icon-hover dropdown">
                    <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                      <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                      <div class="notification text-bg-danger rounded-circle fs-1">5</div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                        <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                            <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                              <span class="d-block fs-2">9:30 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just see the my new admin!</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Event today</h6>
                              <span class="d-block fs-2">9:15 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just a reminder that you have event</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Settings</h6>
                              <span class="d-block fs-2">4:36 PM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">You can customize this template as you want</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                            <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                              <span class="d-block fs-2">9:30 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just see the my new admin!</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                            <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Event today</h6>
                              <span class="d-block fs-2">9:15 AM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">Just a reminder that you have event</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                          <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                            <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="mb-1 fw-semibold">Settings</h6>
                              <span class="d-block fs-2">4:36 PM</span>
                            </div>
                            <span class="d-block text-truncate text-truncate">You can customize this template as you want</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100">See All Notifications</button>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end notification Dropdown -->
                  <!-- ------------------------------- -->

                  <!-- ------------------------------- -->
                  <!-- start profile Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                      <div class="d-flex align-items-center gap-2 lh-base">
                        <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="MaterialM-img" />
                      </div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                      <div class="profile-dropdown position-relative" data-simplebar>
                        <div class="py-3 px-7 pb-0">
                          <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                        </div>
                        <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                          <img src="./recursos/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="MaterialM-img" />
                          <div class="ms-3">
                            <h5 class="mb-0 fs-4">Jonathan Deo</h5>
                            <span class="mb-1 d-block">Admin</span>
                            <p class="mb-0 d-flex align-items-center gap-2">
                              <i class="ti ti-mail fs-4"></i> info@MaterialM.com
                            </p>
                          </div>
                        </div>
                        <div class="message-body">
                          <a href="../main/page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded round">
                              <iconify-icon icon="solar:wallet-2-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Profile</h5>
                              <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                            </div>
                          </a>
                          <a href="../main/app-email.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded round">
                              <iconify-icon icon="solar:inbox-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Inbox</h5>
                              <span class="fs-2 d-block text-body-secondary">Messages & Emails</span>
                            </div>
                          </a>
                          <a href="../main/app-notes.html" class="py-8 px-7 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded round">
                              <iconify-icon icon="solar:checklist-minimalistic-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div class="w-75 v-middle ps-3">
                              <h5 class="mb-1 fs-3 fw-medium">My Task</h5>
                              <span class="fs-2 d-block text-body-secondary">To-do and Daily Tasks</span>
                            </div>
                          </a>
                        </div>
                        <div class="d-grid py-4 px-7 pt-8">
                          <a href="../main/authentication-login.html" class="btn btn-primary">Log Out</a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end profile Dropdown -->
                  <!-- ------------------------------- -->
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </header>
      <!--  Header End -->

      <aside class="left-sidebar with-horizontal">
        <!-- Sidebar scroll-->
        <div>
          <!-- Sidebar navigation-->
          <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
            <ul id="sidebarnav">
              <!-- ============================= -->
              <!-- Home -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <!-- =================== -->
              <!-- Dashboard -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <iconify-icon icon="solar:layers-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/index.html" class="sidebar-link">
                      <i class="ti ti-aperture"></i>
                      <span class="hide-menu">Analytics</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/index2.html" class="sidebar-link">
                      <i class="ti ti-shopping-cart"></i>
                      <span class="hide-menu">eCommerce</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/index3.html" class="sidebar-link">
                      <i class="ti ti-currency-dollar"></i>
                      <span class="hide-menu">CRM</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- ============================= -->
              <!-- Front Pages -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Front Pages</span>
              </li>

              <!-- =================== -->
              <!-- Icon -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:home-angle-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Front Pages</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/frontend-landingpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Homepage</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-aboutpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">About Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-blogpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Blog</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-blogdetailpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Blog Details</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-contactpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Contact Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-portfoliopage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Portfolio</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../main/frontend-pricingpage.html" aria-expanded="false">
                      <span class="rounded-3">
                        <i class="ti ti-circle"></i>
                      </span>
                      <span class="hide-menu">Pricing</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- ============================= -->
              <!-- Apps -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Apps</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link two-column has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <iconify-icon icon="solar:widget-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Apps</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/app-calendar.html" class="sidebar-link">
                      <i class="ti ti-calendar"></i>
                      <span class="hide-menu">Calendar</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/apps-kanban.html" class="sidebar-link">
                      <i class="ti ti-layout-kanban"></i>
                      <span class="hide-menu">Kanban</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/app-chat.html" class="sidebar-link">
                      <i class="ti ti-message-dots"></i>
                      <span class="hide-menu">Chat</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/app-email.html" aria-expanded="false">
                      <span>
                        <i class="ti ti-mail"></i>
                      </span>
                      <span class="hide-menu">Email</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/app-contact.html" class="sidebar-link">
                      <i class="ti ti-phone"></i>
                      <span class="hide-menu">Contact Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/app-contact2.html" class="sidebar-link">
                      <i class="ti ti-list-details"></i>
                      <span class="hide-menu">Contact List</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/app-notes.html" class="sidebar-link">
                      <i class="ti ti-notes"></i>
                      <span class="hide-menu">Notes</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/app-invoice.html" class="sidebar-link">
                      <i class="ti ti-file-text"></i>
                      <span class="hide-menu">Invoice</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/page-user-profile.html" class="sidebar-link">
                      <i class="ti ti-user-circle"></i>
                      <span class="hide-menu">User Profile</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/blog-posts.html" class="sidebar-link">
                      <i class="ti ti-article"></i>
                      <span class="hide-menu">Posts</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/blog-detail.html" class="sidebar-link">
                      <i class="ti ti-details"></i>
                      <span class="hide-menu">Detail</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-shop.html" class="sidebar-link">
                      <i class="ti ti-shopping-cart"></i>
                      <span class="hide-menu">Shop</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-shop-detail.html" class="sidebar-link">
                      <i class="ti ti-basket"></i>
                      <span class="hide-menu">Shop Detail</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-product-list.html" class="sidebar-link">
                      <i class="ti ti-list-check"></i>
                      <span class="hide-menu">List</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-checkout.html" class="sidebar-link">
                      <i class="ti ti-brand-shopee"></i>
                      <span class="hide-menu">Checkout</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-add-product.html" class="sidebar-link">
                      <i class="ti ti-file-plus"></i>
                      <span class="hide-menu">Add Product</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/eco-edit-product.html" class="sidebar-link">
                      <i class="ti ti-file-pencil"></i>
                      <span class="hide-menu">Edit Product</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- PAGES -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">PAGES</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <iconify-icon icon="solar:notes-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Pages</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/page-faq.html" class="sidebar-link">
                      <i class="ti ti-help"></i>
                      <span class="hide-menu">FAQ</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/page-account-settings.html" class="sidebar-link">
                      <i class="ti ti-user-circle"></i>
                      <span class="hide-menu">Account Setting</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/page-pricing.html" class="sidebar-link">
                      <i class="ti ti-currency-dollar"></i>
                      <span class="hide-menu">Pricing</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/widgets-cards.html" class="sidebar-link">
                      <i class="ti ti-cards"></i>
                      <span class="hide-menu">Card</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/widgets-banners.html" class="sidebar-link">
                      <i class="ti ti-ad"></i>
                      <span class="hide-menu">Banner</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/widgets-charts.html" class="sidebar-link">
                      <i class="ti ti-chart-bar"></i>
                      <span class="hide-menu">Charts</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/starter.html" class="sidebar-link">
                      <i class="ti ti-file"></i>
                      <span class="hide-menu">Starter</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../landingpage/index.html" class="sidebar-link">
                      <i class="ti ti-app-window"></i>
                      <span class="hide-menu">Landing Page</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/icon-tabler.html" class="sidebar-link">
                      <i class="ti ti-mood-smile"></i>
                      <span class="hide-menu">Tabler Icon</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/icon-solar.html" class="sidebar-link">
                      <i class="ti ti-mood-smile"></i>
                      <span class="hide-menu">Solar Icon</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- UI -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">UI</span>
              </li>
              <!-- =================== -->
              <!-- UI Elements -->
              <!-- =================== -->
              <li class="sidebar-item mega-dropdown">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:archive-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">UI</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/ui-accordian.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Accordian</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-badge.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Badge</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-buttons.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Buttons</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-dropdowns.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Dropdowns</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-modals.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Modals</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-tab.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Tab</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-tooltip-popover.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Tooltip & Popover</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-notification.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Notification</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-progressbar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Progressbar</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-pagination.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Pagination</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-typography.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Typography</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-bootstrap-ui.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Bootstrap UI</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-breadcrumb.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Breadcrumb</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-offcanvas.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Offcanvas</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-lists.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Lists</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-grid.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Grid</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-carousel.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Carousel</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-scrollspy.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Scrollspy</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-spinner.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Spinner</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/ui-link.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Link</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Forms -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Forms</span>
              </li>
              <!-- =================== -->
              <!-- Forms -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link two-column has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:folder-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Forms</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <!-- form elements -->
                  <li class="sidebar-item">
                    <a href="../main/form-inputs.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Forms Input</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-input-groups.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Input Groups</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-input-grid.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Input Grid</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-checkbox-radio.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Checkbox & Radios</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-bootstrap-switch.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">BT Switch</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-select2.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Select2</span>
                    </a>
                  </li>
                  <!-- form inputs -->
                  <li class="sidebar-item">
                    <a href="../main/form-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Basic Form</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-vertical.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Vertical</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-horizontal.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Horizontal</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-actions.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Actions</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-row-separator.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Row Separator</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-bordered.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Bordered</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/form-detail.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Detail</span>
                    </a>
                  </li>
                  <!-- form wizard -->
                  <li class="sidebar-item">
                    <a href="../main/form-wizard.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Wizard</span>
                    </a>
                  </li>
                  <!-- Quill Editor -->
                  <li class="sidebar-item">
                    <a href="../main/form-editor-quill.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Quill Editor</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Tables -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Tables</span>
              </li>
              <!-- =================== -->
              <!-- Bootstrap Table -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:tuning-square-2-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Tables</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/table-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Basic Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-dark-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Dark Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-sizing.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Sizing Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-layout-coloured.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Coloured Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-datatable-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Basic Initialisation</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-datatable-api.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">API</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/table-datatable-advanced.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Advanced</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Charts -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Charts</span>
              </li>
              <!-- =================== -->
              <!-- Apex Chart -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:chart-square-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Charts</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-line.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Line Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-area.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Area Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-bar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Bar Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-pie.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Pie Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-radial.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Radial Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="../main/chart-apex-radar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Radar Chart</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- multi level -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:airbuds-case-minimalistic-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Multi DD</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../docs/index.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Documentation</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 1</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link has-arrow">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 2</span>
                    </a>
                    <ul aria-expanded="false" class="collapse second-level">
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.1</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.2</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.3</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 3</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>

      <div class="body-wrapper">
        <div class="container-fluid">
          <!--  Row -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body position-relative pb-1">
                  <div class="row justify-content-between">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                      <div>
                        <h4 class="card-title mb-1">
                          Congratulations Jonathan
                        </h4>
                        <p class="mb-0 card-subtitle">
                          You have done 38% more sales
                        </p>
                      </div>
                      <div class="mt-6">
                        <div class="row align-items-center">
                          <div class="col-md-5">
                            <ul class="list-unstyled mb-4 mb-md-0">
                              <li class="d-flex align-items-center mb-9">
                                <div class="bg-primary-subtle rounded-circle-shape px-3 py-2 me-3 rounded-5 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:cart-4-line-duotone" class="fs-7 text-primary"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-1 fs-4">64 new orders</h6>
                                  <p class="mb-0 lh-1">Processing</p>
                                </div>
                              </li>
                              <li class="d-flex align-items-center mb-9">
                                <div class="bg-danger-subtle rounded-circle-shape px-3 py-2 me-3 rounded-5 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:checklist-minimalistic-line-duotone" class="fs-7 text-danger"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-1 fs-4">4 orders</h6>
                                  <p class="mb-0 lh-1">On hold</p>
                                </div>
                              </li>
                              <li class="d-flex align-items-center">
                                <div class="bg-success-subtle rounded-circle-shape px-3 py-2 me-3 rounded-5 d-flex align-items-center justify-content-center">
                                  <iconify-icon icon="solar:banknote-line-duotone" class="fs-7 text-success"></iconify-icon>
                                </div>
                                <div>
                                  <h6 class="mb-1 fs-4">12 orders</h6>
                                  <p class="mb-0 lh-1">Delivered</p>
                                </div>
                              </li>
                            </ul>
                          </div>
                          <div class="col-md-7">
                            <div class="text-end">
                              <img src="./recursos/images/backgrounds/teamwork.png" alt="" class="img-fluid" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="d-flex align-items-baseline justify-content-between mb-2">
                        <div>
                          <h5 class="mb-1 card-title">Total Orders</h5>
                          <p class="mb-0 card-subtitle">Weekly order updates</p>
                        </div>
                        <select class="form-select w-auto bg-light border-0 rounded-pill">
                          <option value="1">This Week</option>
                          <option value="2">April 2023</option>
                          <option value="3">May 2023</option>
                          <option value="4">June 2023</option>
                        </select>
                      </div>
                      <div class="me-n3">
                        <div id="netsells"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h4 class="fs-4 mb-1 card-title">Latest Deal</h4>
                      <p class="mb-0 card-subtitle">Last 7 days</p>
                    </div>
                    <div>
                      <span class="badge rounded-pill fs-2 fw-medium bg-primary-subtle fs-2 fw-medium text-primary">86.5%</span>
                    </div>
                  </div>
                  <div class="my-5">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5 class="mb-0 fs-4">$98,500</h5>
                      <h6 class="mb-0 fs-4">$1,22,900</h6>
                    </div>
                    <div class="progress bg-light w-100 my-2" style="height: 4px">
                      <div class="progress-bar bg-success" role="progressbar" aria-label="Example 8px high" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0">Coupons used: 18/22</p>
                  </div>
                  <h6 class="mb-3 pt-2">Recent Purchasers</h6>
                  <ul class="hstack mb-0">
                    <li class="ms-n2">
                      <a href="javascript:void(0)" class="">
                        <img src="./recursos/images/profile/user-5.jpg" class="rounded-circle border border-2 border-transparent" width="40" height="40" alt="" />
                      </a>
                    </li>
                    <li class="ms-n2">
                      <a href="javascript:void(0)" class="">
                        <img src="./recursos/images/profile/user-2.jpg" class="rounded-circle border border-2 border-transparent" width="40" height="40" alt="" />
                      </a>
                    </li>
                    <li class="ms-n2">
                      <a href="javascript:void(0)" class="">
                        <img src="./recursos/images/profile/user-3.jpg" class="rounded-circle border border-2 border-transparent" width="40" height="40" alt="" />
                      </a>
                    </li>
                    <li class="ms-n2">
                      <a href="javascript:void(0)">
                        <img src="./recursos/images/profile/user-4.jpg" class="rounded-circle border border-2 border-transparent" width="40" height="40" alt="" />
                      </a>
                    </li>
                    <li class="ms-n2">
                      <a href="javascript:void(0)" class="bg-secondary-subtle text-secondary rounded-circle border border-2 border-transparent d-flex align-items-center justify-content-center round-40">
                        +8
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h4 class="fs-4 mb-1 card-title">Customers</h4>
                      <p class="mb-0 card-subtitle">Last 7 days</p>
                    </div>
                    <div>
                      <h5 class="fs-4 mb-1 text-end">6,380</h5>
                      <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success fs-2 fw-medium">+26.5%</span>
                    </div>
                  </div>
                  <div id="customers" class="my-4 py-3"></div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                      <span class="circle-shape bg-primary rounded-pill me-2"></span>
                      <p class="mb-0">Apr 07 - Apr 14</p>
                    </div>
                    <p class="mb-0">6,380</p>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <span class="circle-shape bg-light rounded-pill me-2"></span>
                      <p class="mb-0">Last Week</p>
                    </div>
                    <p class="mb-0">4,298</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between pb-1">
                    <div>
                      <h4 class="mb-1 card-title fs-4">Payments</h4>
                      <p class="mb-0 card-subtitle">Last 7 days</p>
                    </div>
                    <div class="text-end">
                      <h5 class="mb-1 fs-4 text-end fw-bold">12,389</h5>
                      <span class="badge rounded-pill fs-2 fw-medium bg-danger-subtle fs-2 fw-medium text-danger">-3.8%</span>
                    </div>
                  </div>
                  <div class="me-n4 mb-6">
                    <div id="total-orders" class="rounded-bars "></div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                      <span class="circle-shape bg-primary rounded-pill me-2"></span>
                      <p class="mb-0">Paypal</p>
                    </div>
                    <p class="mb-0">52%</p>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <span class="circle-shape bg-light rounded-pill me-2"></span>
                      <p class="mb-0">Credit Card</p>
                    </div>
                    <p class="mb-0">48%</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h4 class="mb-1 fs-4 card-title">Products</h4>
                      <p class="mb-0 card-subtitle">Last 7 days</p>
                    </div>
                    <div>
                      <h5 class="mb-1 fs-4 text-end fw-bold">432</h5>
                      <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle fs-2 fw-medium text-success text-end">+26.5%</span>
                    </div>
                  </div>
                  <div class="my-7">
                    <div id="products" class="my-8"></div>
                  </div>
                  <p class="mb-0 text-center fs-2">
                    Profit more than last month
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xxl-8">
              <div class="card overflow-hidden">
                <div class="card-body pb-0">
                  <h4 class="fs-4 mb-1 card-title">Popular Products</h4>
                  <p class="mb-0 card-subtitle">Total 9k Visitors</p>
                </div>
                <div data-simplebar class="position-relative" style="height: 465px">
                  <div class="table-responsive overflow-x-auto products-tabel" data-simplebar>
                    <table class="table text-nowrap  mb-0 align-middle table-hover">
                      <thead class="fs-4">
                        <tr>
                          <th class="fs-3 px-4">Products</th>
                          <th class="fs-3">Payment</th>
                          <th class="fs-3">Status</th>
                          <th class="fs-3"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center product">
                              <img src="./recursos/images/products/s1.jpg" class="img-fluid flex-shrink-0 rounded" width="60" height="60" />
                              <div class="ms-3 product-title">
                                <h6 class="fs-3 mb-0 text-truncate-2">
                                  iPhone 13 pro max-Pacific Blue-128GB storage
                                </h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h5 class="mb-0 fs-4">
                              $180 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Partially paid</p>
                            <div class="progress bg-light w-100" style="height: 4px">
                              <div class="progress-bar bg-warning" role="progressbar" aria-label="Example 4px high" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary">Confirmed</span>
                          </td>
                          <td>
                            <div class="dropdown dropstart">
                              <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-plus"></i>Add
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-edit"></i>Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-trash"></i>Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center product">
                              <img src="./recursos/images/products/s2.jpg" class="img-fluid flex-shrink-0 rounded" width="60" height="60" />
                              <div class="ms-3 product-title">
                                <h6 class="fs-3 mb-0 text-truncate-2">
                                  Apple MacBook Pro 13 inch-M1-8/256GB-space
                                </h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h5 class="mb-0 fs-4">
                              $120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Full paid</p>
                            <div class="progress bg-light w-100" style="height: 4px">
                              <div class="progress-bar bg-success" role="progressbar" aria-label="Example 4px high" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success">Confirmed</span>
                          </td>
                          <td>
                            <div class="dropdown dropstart">
                              <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-plus"></i>Add
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-edit"></i>Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-trash"></i>Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center product">
                              <img src="./recursos/images/products/s3.jpg" class="img-fluid flex-shrink-0 rounded" width="60" height="60" />
                              <div class="ms-3 product-title">
                                <h6 class="fs-3 mb-0 text-truncate-2">
                                  PlayStation 5 DualSense Wireless Controller
                                </h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h5 class="mb-0 fs-4">
                              $120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Cancelled</p>
                            <div class="progress bg-light w-100" style="height: 4px">
                              <div class="progress-bar bg-danger" role="progressbar" aria-label="Example 4px high" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-danger-subtle text-danger">Cancelled</span>
                          </td>
                          <td>
                            <div class="dropdown dropstart">
                              <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-plus"></i>Add
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-edit"></i>Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-trash"></i>Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center product">
                              <img src="./recursos/images/products/s5.jpg" class="img-fluid flex-shrink-0 rounded" width="60" height="60" />
                              <div class="ms-3 product-title">
                                <h6 class="fs-3 mb-0 text-truncate-2">
                                  Amazon Basics Mesh, Mid-Back, Swivel Office
                                  De...
                                </h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <h5 class="mb-0 fs-4">
                              $120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Partially paid</p>
                            <div class="progress bg-light w-100" style="height: 4px">
                              <div class="progress-bar bg-warning" role="progressbar" aria-label="Example 4px high" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge rounded-pill fs-2 fw-medium bg-secondary-subtle text-secondary">Confirmed</span>
                          </td>
                          <td>
                            <div class="dropdown dropstart">
                              <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-plus"></i>Add
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-edit"></i>Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-trash"></i>Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="border-bottom-0">
                            <div class="d-flex align-items-center product">
                              <img src="./recursos/images/products/s8.jpg" class="img-fluid flex-shrink-0 rounded" width="60" height="60" />
                              <div class="ms-3 product-title">
                                <h6 class="fs-3 mb-0 text-truncate-2">
                                  Sony X85J 75 Inch Sony 4K Ultra HD LED Smart
                                  G...
                                </h6>
                              </div>
                            </div>
                          </td>
                          <td class="border-bottom-0">
                            <h5 class="mb-0 fs-4">
                              $120 <span class="text-muted">/499</span>
                            </h5>
                            <p class="text-muted mb-2">Full paid</p>
                            <div class="progress bg-light w-100" style="height: 4px">
                              <div class="progress-bar bg-success" role="progressbar" aria-label="Example 4px high" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td class="border-bottom-0">
                            <span class="badge rounded-pill fs-2 fw-medium bg-success-subtle text-success">Confirmed</span>
                          </td>
                          <td class="border-bottom-0">
                            <div class="dropdown dropstart">
                              <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-plus"></i>Add
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-edit"></i>Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                    <i class="fs-4 ti ti-trash"></i>Delete
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4">
              <div class="card acedamic w-100">
                <div class="card-body p-4">
                  <div class="d-flex mb-4 justify-content-between align-items-center">
                    <div>
                      <h4 class="card-title">Upcoming Schedules</h4>
                      <p class="mb-0 card-subtitle">Our corporate events</p>
                    </div>
                    <div class="dropdown">
                      <button id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" class="rounded-circle btn-transparent rounded-circle btn-sm px-1 btn shadow-none">
                        <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li>
                          <a class="dropdown-item" href="javascript:void(0)">Action</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs nav-justified theme-tab table-responsive flex-nowrap" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link py-1 fs-3 me-1 active w-100" data-bs-toggle="tab" href="#one1" role="tab">
                        <span>All</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link py-1 fs-3 me-1 w-100" data-bs-toggle="tab" href="#two2" role="tab">
                        <span>Delivered</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link py-1 fs-3" data-bs-toggle="tab" href="#three3" role="tab">
                        <span>Shipping</span>
                      </a>
                    </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content mt-2">
                    <div class="tab-pane active" id="one1" role="tabpanel">
                      <div class="tab-content">
                        <div class="upcoming-schedules position-relative" data-simplebar>
                          <!-- ------------- -->
                          <!-- 3 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-warning d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:scooter-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Scooter Freight</span>
                              <h6 class="mt-1 mb-0">#1245-456201</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">All</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Drainage</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">The lowland zone</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 1 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-primary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:bus-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Bus Freight</span>
                              <h6 class="mt-1 mb-0">#9002-125423</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">All</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">Sicily, Italy</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Hektor Container Hotel</span>
                              <h6 class="mt-1 mb-0">Tallin, EST</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 2 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-secondary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:tram-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Tram Freight</span>
                              <h6 class="mt-1 mb-0">#1245-780652</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">Delivered</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">
                                Lester, United Kingdom
                              </h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Laxmi Empire Hotel</span>
                              <h6 class="mt-1 mb-0">Mumbai, India</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="two2" role="tabpanel">
                      <div class="tab-content">
                        <div class="upcoming-schedules position-relative" data-simplebar>
                          <!-- ------------- -->
                          <!-- 3 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-warning d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:scooter-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Scooter Freight</span>
                              <h6 class="mt-1 mb-0">#1245-456201</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">Delivered</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Drainage</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">The lowland zone</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 1 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-primary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:bus-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Bus Freight</span>
                              <h6 class="mt-1 mb-0">#9002-125423</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">Delivered</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">Sicily, Italy</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Hektor Container Hotel</span>
                              <h6 class="mt-1 mb-0">Tallin, EST</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 2 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-secondary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:tram-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Tram Freight</span>
                              <h6 class="mt-1 mb-0">#1245-780652</h6>
                            </div>
                            <span class="ms-auto badge bg-success-subtle text-success rounded-pill fs-2 fw-medium">Delivered</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">
                                Lester, United Kingdom
                              </h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Laxmi Empire Hotel</span>
                              <h6 class="mt-1 mb-0">Mumbai, India</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="three3" role="tabpanel">
                      <div class="tab-content">
                        <div class="position-relative upcoming-schedules" data-simplebar>
                          <!-- ------------- -->
                          <!-- 2 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-secondary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:tram-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Tram Freight</span>
                              <h6 class="mt-1 mb-0">#1245-780652</h6>
                            </div>
                            <span class="ms-auto badge bg-primary-subtle text-primary rounded-pill fs-2 fw-medium">Shipping</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">
                                Lester, United Kingdom
                              </h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-secondary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Laxmi Empire Hotel</span>
                              <h6 class="mt-1 mb-0">Mumbai, India</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 3 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-warning d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:scooter-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Scooter Freight</span>
                              <h6 class="mt-1 mb-0">#1245-456201</h6>
                            </div>
                            <span class="ms-auto badge bg-primary-subtle text-primary rounded-pill fs-2 fw-medium">Shipping</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Drainage</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-warning fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">The lowland zone</span>
                              <h6 class="mt-1 mb-0">United Kingdom</h6>
                            </div>
                          </div>
                          <!-- ------------- -->
                          <!-- 1 -->
                          <!-- ------------- -->
                          <div class="d-flex align-items-center gap-3 mt-3">
                            <span class="round-40 rounded text-bg-primary d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:bus-line-duotone" class="fs-7"></iconify-icon>
                            </span>
                            <div>
                              <span class="text-body-color fw-medium">Bus Freight</span>
                              <h6 class="mt-1 mb-0">#9002-125423</h6>
                            </div>
                            <span class="ms-auto badge bg-primary-subtle text-primary rounded-pill fs-2 fw-medium">Shipping</span>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 after-line">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:soundwave-circle-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Messina Harbor</span>
                              <h6 class="mt-1 mb-0">Sicily, Italy</h6>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 mt-3 border-bottom pb-4">
                            <div class="round-40 border rounded-circle d-flex align-items-center justify-content-center">
                              <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary fs-7"></iconify-icon>
                            </div>
                            <div>
                              <span class="text-body-color fw-medium">Hektor Container Hotel</span>
                              <h6 class="mt-1 mb-0">Tallin, EST</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8 d-flex align-items-stretch">
              <div class="card mb-7 mb-lg-0 w-100">
                <div class="card-body pb-0">
                  <div class="d-md-flex justify-content-between mb-10">
                    <div class="mb-10 mb-md-0">
                      <h4 class="card-title">Latest reviews</h4>
                      <p class="mb-0 card-subtitle">
                        Reviewed received across all channels
                      </p>
                    </div>
                    <div class="d-flex align-items-center">
                      <form class="position-relative me-3 w-100">
                        <input type="text" class="form-control search-chat py-2 ps-5 rounded-pill" id="text-srh" placeholder="Search" />
                        <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6 position-absolute top-50 start-0 translate-middle-y ms-3"></iconify-icon>
                      </form>
                      <div class="dropdown">
                        <a href="javascript:void(0)" class="btn border shadow-none py-2 px-3" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                              <i class="fs-4 ti ti-plus"></i>Add
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                              <i class="fs-4 ti ti-edit"></i>Edit
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                              <i class="fs-4 ti ti-trash"></i>Delete
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-responsive overflow-x-auto latest-reviews-table" data-simplebar>
                  <table class="table mb-0 align-middle text-nowrap table-hover">
                    <thead class="fs-4">
                      <tr>
                        <th class="ps-4">#</th>
                        <th>Customer</th>
                        <th>Reviews</th>
                        <th>Time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="ps-4">
                          <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-truncate">
                            <img src="./recursos/images/profile/user-6.jpg" alt="" class="img-fluid rounded-circle flex-shrink-0" width="32" height="32" />
                            <div class="ms-6">
                              <h5 class="mb-0 fs-4">Arlene McCoy</h5>
                              <p class="mb-0 fw-normal text-dark">macoy@arlene.com</p>
                              <ul class="list-unstyled d-flex align-items-center mb-0">
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-half-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="" href="javascript:void(0)">
                                    <i class="ti ti-star text-warning"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="product-reviews">
                            <p class="text-dark mb-0 fw-normal text-truncate-2">
                              This theme is great. Clean and easy to
                              understand. Perfect for those who don't have
                              <br />
                              time to... <a href="javascript:void(0)">See more</a>
                            </p>
                          </div>
                        </td>
                        <td>
                          <p class="mb-0">Nov 8</p>
                        </td>
                        <td>
                          <div class="dropdown dropstart">
                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-plus"></i>Add
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-edit"></i>Edit
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-trash"></i>Delete
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="ps-4">
                          <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-truncate">
                            <img src="./recursos/images/profile/user-3.jpg" alt="" class="img-fluid rounded-circle flex-shrink-0" width="32" height="32" />
                            <div class="ms-6">
                              <h5 class="mb-0 fs-4">Jerome Bell</h5>
                              <p class="mb-0 fw-normal text-dark">belljerome@yahoo.com</p>
                              <ul class="list-unstyled d-flex align-items-center mb-0">
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="" href="javascript:void(0)">
                                    <i class="ti ti-star text-warning"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="product-reviews">
                            <p class="text-dark mb-0 fw-normal text-truncate-2">
                              It's a Mac, after all. Once you've gone Mac, there's no going back. My first Mac lasted over nine years.
                            </p>
                          </div>
                        </td>
                        <td>
                          <p class="mb-0">Nov 8</p>
                        </td>
                        <td>
                          <div class="dropdown dropstart">
                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-plus"></i>Add
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-edit"></i>Edit
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-trash"></i>Delete
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="ps-4">
                          <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-truncate">
                            <img src="./recursos/images/profile/user-4.jpg" alt="" class="img-fluid rounded-circle flex-shrink-0" width="32" height="32" />
                            <div class="ms-6">
                              <h5 class="mb-0 fs-4">Annette Black</h5>
                              <p class="mb-0 fw-normal text-dark">blackanne@yahoo.com</p>
                              <ul class="list-unstyled d-flex align-items-center mb-0">
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="" href="javascript:void(0)">
                                    <i class="ti ti-star text-warning"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="product-reviews">
                            <p class="text-dark mb-0 fw-normal text-truncate-2">
                              The controller is quite comfy for me. Despite its increased size, the controller still fits well in my hands.
                            </p>
                          </div>
                        </td>
                        <td>
                          <p class="mb-0">Nov 8</p>
                        </td>
                        <td>
                          <div class="dropdown dropstart">
                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-plus"></i>Add
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-edit"></i>Edit
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-trash"></i>Delete
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="ps-4">
                          <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" />
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center text-truncate">
                            <img src="./recursos/images/profile/user-5.jpg" alt="" class="img-fluid rounded-circle flex-shrink-0" width="32" height="32" />
                            <div class="ms-6">
                              <h5 class="mb-0 fs-4">Albert Flores</h5>
                              <p class="mb-0 fw-normal text-dark">albertflo9@gmail.com</p>
                              <ul class="list-unstyled d-flex align-items-center mb-0">
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="me-1" href="javascript:void(0)">
                                    <i class="ti ti-star-half-filled text-warning"></i>
                                  </a>
                                </li>
                                <li>
                                  <a class="" href="javascript:void(0)">
                                    <i class="ti ti-star text-warning"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="product-reviews">
                            <p class="text-dark mb-0 fw-normal text-truncate-2">
                              This theme is great. Perfect for those who don't have time to start everything from scratch.
                            </p>
                          </div>
                        </td>
                        <td>
                          <p class="mb-0">Nov 8</p>
                        </td>
                        <td>
                          <div class="dropdown dropstart">
                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="material-symbols-outlined fs-7 d-block">more_vert</i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-plus"></i>Add
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-edit"></i>Edit
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="javascript:void(0)">
                                  <i class="fs-4 ti ti-trash"></i>Delete
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-10 px-4 pb-4">
                  <p class="mb-0 fw-normal">1-6 of 32</p>
                  <a href="javascript:void(0)" class="btn btn-primary">View All Reviews</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 d-flex align-items-stretch">
              <div class="card w-100 mb-0">
                <div class="card-body">
                  <h4 class="fs-4 mb-1 card-title">Sales from Locations</h4>
                  <p class="mb-0 card-subtitle">United Stats</p>
                  <div class="mt-3">
                    <div id="usa" style="height: 290px"></div>
                  </div>
                  <div class="border-top pt-7">
                    <div class="hstack gap-10 mb-10">
                      <h6 class="mb-0 flex-shrink-0" style="width: 25px">
                        LA
                      </h6>
                      <div class="progress mt-1 w-100" style="height: 5px">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <h6 class="mb-0 flex-shrink-0" style="width: 35px">
                        28%
                      </h6>
                    </div>
                    <div class="hstack gap-4 mb-10">
                      <h6 class="mb-0 flex-shrink-0" style="width: 25px">
                        NY
                      </h6>
                      <div class="progress mt-1 w-100" style="height: 5px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 21%" aria-valuenow="21" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <h6 class="mb-0 flex-shrink-0" style="width: 35px">
                        21%
                      </h6>
                    </div>
                    <div class="hstack gap-4 mb-10">
                      <h6 class="mb-0 flex-shrink-0" style="width: 25px">
                        KA
                      </h6>
                      <div class="progress mt-1 w-100" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <h6 class="mb-0 flex-shrink-0" style="width: 35px">
                        18%
                      </h6>
                    </div>
                    <div class="hstack gap-4">
                      <h6 class="mb-0 flex-shrink-0" style="width: 25px">
                        AZ
                      </h6>
                      <div class="progress mt-1 w-100" style="height: 5px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <h6 class="mb-0 flex-shrink-0" style="width: 35px">
                        12%
                      </h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end row -->
        </div>
      </div>
      <button class="btn btn-success p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        <i class="icon ti ti-settings fs-7"></i>
      </button>

      <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
          <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
            Settings
          </h4>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body h-n80" data-simplebar>
          <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

          <div class="d-flex flex-row gap-3 customizer-box" role="group">
            <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="light-layout">
              <i class="icon ti ti-brightness-up fs-7 me-2"></i>Light
            </label>

            <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="dark-layout">
              <i class="icon ti ti-moon fs-7 me-2"></i>Dark
            </label>
          </div>

          <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
          <div class="d-flex flex-row gap-3 customizer-box" role="group">
            <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="ltr-layout">
              <i class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR
            </label>

            <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="rtl-layout">
              <i class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL
            </label>
          </div>

          <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

          <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
            <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="BLUE_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>

            <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="AQUA_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>

            <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="PURPLE_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>

            <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Green_Theme')" for="green-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="GREEN_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>

            <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Cyan_Theme')" for="cyan-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="CYAN_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>

            <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Orange_Theme')" for="orange-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="ORANGE_THEME">
              <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                <i class="ti ti-check text-white d-flex icon fs-5"></i>
              </div>
            </label>
          </div>

          <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
          <div class="d-flex flex-row gap-3 customizer-box" role="group">
            <div>
              <input type="radio" class="btn-check" name="page-layout" id="vertical-layout" autocomplete="off" />
              <label class="btn p-9 btn-outline-primary rounded-2" for="vertical-layout">
                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
              </label>
            </div>
            <div>
              <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout" autocomplete="off" />
              <label class="btn p-9 btn-outline-primary rounded-2" for="horizontal-layout">
                <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
              </label>
            </div>
          </div>

          <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

          <div class="d-flex flex-row gap-3 customizer-box" role="group">
            <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="boxed-layout">
              <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed
            </label>

            <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="full-layout">
              <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full
            </label>
          </div>

          <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
          <div class="d-flex flex-row gap-3 flex-wrap customizer-box" role="group">
            <a href="javascript:void(0)" class="fullsidebar">
              <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar" autocomplete="off" />
              <label class="btn p-9 btn-outline-primary rounded-2" for="full-sidebar">
                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full
              </label>
            </a>
            <div>
              <input type="radio" class="btn-check" name="sidebar-type" id="mini-sidebar" autocomplete="off" />
              <label class="btn p-9 btn-outline-primary rounded-2" for="mini-sidebar">
                <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse
              </label>
            </div>
          </div>

          <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

          <div class="d-flex flex-row gap-3 customizer-box" role="group">
            <input type="radio" class="btn-check" name="card-layout" id="card-with-border" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="card-with-border">
              <i class="icon ti ti-border-outer fs-7 me-2"></i>Border
            </label>

            <input type="radio" class="btn-check" name="card-layout" id="card-without-border" autocomplete="off" />
            <label class="btn p-9 btn-outline-primary rounded-2" for="card-without-border">
              <i class="icon ti ti-border-none fs-7 me-2"></i>Shadow
            </label>
          </div>
        </div>
      </div>
      <script>
  function handleColorTheme(e) {
    document.documentElement.setAttribute("data-color-theme", e);
  }
</script>
    </div>

    <!--  Search Bar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
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
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <script src="./recursos/js/vendor.min.js"></script>
  <!-- Import Js Files -->
  <script src="./recursos/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./recursos/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="./recursos/js/theme/app.init.js"></script>
  <script src="./recursos/js/theme/theme.js"></script>
  <script src="./recursos/js/theme/app.min.js"></script>
  <script src="./recursos/js/theme/sidebarmenu.js"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  <!-- highlight.js (code view) -->
  <script src="./recursos/js/highlights/highlight.min.js"></script>
  <script>
  hljs.initHighlightingOnLoad();


  document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
    codeBlock.textContent = codeBlock.innerHTML;
  });
</script>
  <script src="./recursos/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="./recursos/libs/jvectormap/jquery-jvectormap.min.js"></script>
  <script src="./recursos/js/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
  <script src="./recursos/js/dashboards/dashboard1.js"></script>
</body>

</html>
