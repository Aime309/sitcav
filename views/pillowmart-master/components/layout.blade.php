<!doctype html>
<html lang="{{ $_ENV["APP_LOCALE"] }}" x-data='{
    reservedProducts: $persist([]),
    userId: @json(auth()->id()),
    colorScheme: matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light",

    get reservedProductsSubTotal() {
      return this.reservedProducts.reduce(
        (subtotal, product) => subtotal + product.price * product.quantity,
        0,
      );
    },

    get reservedProductsTotal() {
      return this.reservedProductsSubTotal;
    },

    removeReservedProduct(reservedProduct) {
      this.reservedProducts = this.reservedProducts.filter(product => {
        return product.id !== reservedProduct.id;
      });
    },
  }' x-init="
    if (!userId) {
      reservedProducts = [];
    }

    matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
      colorScheme = event.matches ? 'dark' : 'light';
    });
  ">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="color-scheme" content="light dark" />
    <title>{{ $_ENV["APP_NAME"] }}</title>
    <base href="{{ Flight::getUrl('index') . ECOMMERCE_VIEWS_PATH . '/' }}" />
    <link rel="icon" href="./img/favicon.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" />
    <!-- animate CSS -->
    <link rel="stylesheet" href="./css/animate.css" />
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="./css/owl.carousel.min.css" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="./css/all.css" />
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="./css/flaticon.css" />
    <link rel="stylesheet" href="./css/themify-icons.css" />
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="./css/magnific-popup.css" />

    @if ($withslick ?? false)
    <!-- swiper CSS -->
    <link rel="stylesheet" href="./css/slick.css" />
    @endif

    @if ($withniceselect ?? false)
    <link rel="stylesheet" href="./css/nice-select.css" />
    @endif

    <!-- style CSS -->
    <link rel="stylesheet" href="./css/style.css" />

    <script
      src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"
      defer></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
      input[type=number]::-webkit-inner-spin-button,
      input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
    </style>
  </head>

  <body>
    <x-header />
    {!! $slot !!}
    <x-footer />

    <!-- jquery plugins here-->
    <script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- easing js -->
    <script src="./js/jquery.magnific-popup.js"></script>
    <!-- swiper js -->
    <script src="./js/swiper.min.js"></script>
    <!-- swiper js -->
    <script src="./js/mixitup.min.js"></script>
    <!-- particles js -->
    <script src="./js/owl.carousel.min.js"></script>
    <script src="./js/jquery.nice-select.min.js"></script>
    <!-- slick js -->
    <script src="./js/slick.min.js"></script>
    <script src="./js/jquery.counterup.min.js"></script>
    <script src="./js/waypoints.min.js"></script>
    <script src="./js/contact.js"></script>
    <script src="./js/jquery.ajaxchimp.min.js"></script>
    <script src="./js/jquery.form.js"></script>
    <script src="./js/jquery.validate.min.js"></script>
    <script src="./js/mail-script.js"></script>
    <!-- custom js -->
    <script src="./js/custom.js"></script>
  </body>

</html>
