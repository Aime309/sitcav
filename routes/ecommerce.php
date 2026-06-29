<?php

declare(strict_types=1);

use App\Http\Controllers\Ecommerce\ConfirmationController;
use App\Http\Controllers\Ecommerce\IndexController;
use App\Http\Controllers\Ecommerce\LoginController;
use App\Http\Controllers\Ecommerce\OAuth2Controller;
use App\Http\Controllers\Ecommerce\ProductsController;
use App\Http\Controllers\Ecommerce\ReservationsController;
use App\Http\Controllers\Ecommerce\UsersController;
use App\Http\Middleware\Ecommerce\Authenticate;
use App\Http\Middleware\Ecommerce\Authorize;
use App\Http\Middleware\Ecommerce\RedirectIfAuthenticated;
use App\Policies\Ecommerce\IndexConfirmationPolicy;
use eftec\bladeone\BladeOne;
use flight\Container;
use GuzzleHttp\Client;
use Leaf\Exception\Handler\PrettyPageHandler;
use Leaf\Exception\Run;
use Psr\Http\Client\ClientInterface;

const ECOMMERCE_VIEWS_PATH = "views/pillowmart-master";
define("BLADE_ONE", new BladeOne(ECOMMERCE_VIEWS_PATH));

Flight::group(
  "/ecommerce",
  static function (): void {
    Flight::router()->get('/', [IndexController::class, '__invoke'])->setAlias("ecommerce.index");
    Flight::router()->get('/product_list', [ProductsController::class, 'index'])->setAlias("ecommerce.product_list");
    Flight::router()->get('/single-product/@id', [ProductsController::class, 'show'])->setAlias('ecommerce.single-product.@id');

    Flight::router()->get('/cart', static function (): void {
      Flight::render("cart");
    })
      ->addMiddleware(Authenticate::class)
      ->addMiddleware(Authorize::class)
      ->setAlias("ecommerce.cart");

    Flight::router()->get("/checkout", static function (): void {
      Flight::render("checkout", ["message" => flash()->display()]);
    })
      ->addMiddleware(Authenticate::class)
      ->addMiddleware(Authorize::class)
      ->setAlias("ecommerce.checkout");

    Flight::router()->get("/confirmation/@id", [ConfirmationController::class, 'renderConfirmationById'])
      ->addMiddleware(Authenticate::class)
      ->addMiddleware(Authorize::class)
      ->addMiddleware(IndexConfirmationPolicy::class)
      ->setAlias("ecommerce.confirmation.@id");

    Flight::post("/reservations", [ReservationsController::class, 'create'])
      ->addMiddleware(Authenticate::class)
      ->addMiddleware(Authorize::class)
      ->setAlias("ecommerce.reservations");

    Flight::group(
      "/login",
      static function (): void {
        Flight::router()->get("/", static function (): void {
          Flight::render("login", ["message" => flash()->display()]);
        })->setAlias("ecommerce.login");

        Flight::post("/", [LoginController::class, 'authenticate']);
      },
      [RedirectIfAuthenticated::class],
    );

    Flight::group(
      "/register",
      static function (): void {
        Flight::router()->get("/", static function (): void {
          Flight::render("register", ["message" => flash()->display()]);
        })->setAlias("ecommerce.register");

        Flight::post("/", [UsersController::class, 'create']);
      },
      [RedirectIfAuthenticated::class],
    );

    Flight::router()->get("/logout", static function (): void {
      auth()->logout();
      Flight::redirect(Flight::request()->referrer);
    })->setAlias("ecommerce.logout");

    Flight::router()->get("/oauth2/google", [OAuth2Controller::class, 'google'])->setAlias("ecommerce.oauth2.google");

    Flight::router()->get("/(@page)", static function (?string $page): void {
      Flight::render($page ?? "index", ["message" => flash()->display()]);
    });
  },
  [
    static function (): void {
      $run = new Run();
      $prettyPageHandler = new PrettyPageHandler();

      $run
        ->pushHandler(
          $prettyPageHandler
            ->addEditor(PrettyPageHandler::EDITOR_SUBLIME, "subl:%file:%line")
            ->setEditor(PrettyPageHandler::EDITOR_SUBLIME),
        )
        ->register();
    },
    static function (): void {
      Flight::map("render", static function (
        string $file,
        array $data = [],
        string $key = "",
      ): void {
        $html = BLADE_ONE->run($file, $data);

        if ($key) {
          BLADE_ONE->share($key, $html);

          return;
        }

        echo $html;
      });

      if (!is_dir("compiles")) {
        mkdir("compiles");
      }
    },
    static function (): void {
      Container::getInstance()->singleton(ClientInterface::class, new Client([
        "base_uri" =>
        $_ENV["APP_ENV"] === "production"
          ? Flight::request()->getBaseUrl() . "/"
          : "http://localhost:81/",
      ]));
    }
  ],
);
