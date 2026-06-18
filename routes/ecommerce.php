<?php

declare(strict_types=1);

use eftec\bladeone\BladeOne;
use GuzzleHttp\Client;
use Leaf\Exception\Handler\PrettyPageHandler;
use Leaf\Exception\Run;
use Leaf\Flash;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;

const ECOMMERCE_VIEWS_PATH = "views/pillowmart-master";

define(
  "API_CLIENT",
  new Client([
    "base_uri" =>
    $_ENV["APP_ENV"] === "production"
      ? Flight::request()->getBaseUrl() . "/"
      : "http://localhost:81/",
  ]),
);

define("BLADE_ONE", new BladeOne(ECOMMERCE_VIEWS_PATH));

define("AUTHENTICATE", static function (): void {
  if (!auth()->user()) {
    Flight::redirect(Flight::getUrl("ecommerce.login"));

    exit();
  }
});

define("REDIRECT_IF_AUTHENTICATED", static function (): void {
  if (auth()->user()) {
    Flight::redirect(Flight::request()->referrer);

    exit();
  }
});

define("AUTHORIZE", static function (): void {
  if (!auth()->user()?->is("Client")) {
    Flight::redirect(Flight::request()->referrer);

    exit();
  }
});

Flight::group(
  "/ecommerce",
  static function (): void {
    Flight::route("GET /", static function (): void {
      Flight::render("index", [
        "products" => json_decode(
          API_CLIENT->get("." . Flight::getUrl("api.products.pinned"))
            ->getBody()
            ->getContents(),
          true,
        ),
        "trendingItems" => json_decode(
          API_CLIENT->get("." . Flight::getUrl("api.products.trending"))
            ->getBody()
            ->getContents(),
          true,
        ),
      ]);
    })->setAlias("ecommerce.index");

    Flight::route("GET /product_list", static function (): void {
      Flight::render("product_list", [
        "products" => json_decode(
          API_CLIENT->get("." . Flight::getUrl("api.products"))
            ->getBody()
            ->getContents(),
          true,
        ),
        "categories" => json_decode(
          API_CLIENT->get("." . Flight::getUrl("api.products.categories"))
            ->getBody()
            ->getContents(),
          true,
        ),
        "types" => json_decode(
          API_CLIENT->get("." . Flight::getUrl("api.products.types"))
            ->getBody()
            ->getContents(),
          true,
        ),
      ]);
    })->setAlias("ecommerce.product_list");

    Flight::route(
      "GET /single-product/@" . auth()->config("id.key"),
      static function (string $id): void {
        Flight::render("single-product", [
          "product" => json_decode(
            API_CLIENT->get(Flight::getUrl("api.products.@" . auth()->config("id.key"), [$id]))
              ->getBody()
              ->getContents(),
            true,
          ),
        ]);
      }
    )->setAlias("ecommerce.single-product.@" . auth()->config("id.key"));

    Flight::route("GET /cart", static function (): void {
      Flight::render("cart");
    })
      ->addMiddleware(AUTHENTICATE)
      ->addMiddleware(AUTHORIZE)
      ->setAlias("ecommerce.cart");

    Flight::route("GET /checkout", static function (): void {
      Flight::render("checkout", ["message" => Flash::display()]);
    })
      ->addMiddleware(AUTHENTICATE)
      ->addMiddleware(AUTHORIZE)
      ->setAlias("ecommerce.checkout");

    Flight::route(
      "GET /confirmation/@" . auth()->config("id.key"),
      static function (string $id): void {
        $reservation = db()
          ->select("reservations")
          ->with("reservation_details", "reservation_id")
          ->find($id);

        $reservation["total"] = 0;

        foreach ($reservation["reservation_details"] as &$details) {
          $details["product"] = db()
            ->select("products")
            ->find($details["product_id"]);

          unset($details["product_id"]);
          unset($details["reservation_id"]);

          $details["product"]["sources"] = json_decode(
            $details["product"]["sources"],
          );

          $reservation["total"] +=
            $details["quantity"] * $details["product"]["price"];
        }

        Flight::render("confirmation", compact("reservation"));
      }
    )
      ->addMiddleware(AUTHENTICATE)
      ->addMiddleware(AUTHORIZE)
      ->addMiddleware(static function (array $params): void {
        $reservation = db()
          ->select("reservations")
          ->find($params[auth()->config("id.key")]);

        if (!$reservation) {
          Flight::redirect(Flight::getUrl("ecommerce.index"));

          exit();
        }

        if ($reservation["user_id"] !== auth()->id()) {
          Flight::redirect(Flight::getUrl("ecommerce.index"));

          exit();
        }
      })
      ->setAlias("ecommerce.confirmation.@id");

    Flight::route("POST /reservations", static function (): void {
      try {
        $data = Flight::request()->data;

        if (!$data["selector"]) {
          throw new Error("Por favor acepta los términos y condiciones");
        }

        if (!auth()->update($data["user"])) {
          Flash::set(auth()->errors());
          Flight::redirect(Flight::request()->referrer);

          exit();
        }

        $reservation[auth()->config("id.key")] = uniqid();

        if (!auth()->user()->reservations()->create($reservation)) {
          Flash::set(db()->errors());
          Flight::redirect(Flight::request()->referrer);

          exit();
        }

        foreach ($data["reservedProducts"] as $reservedProduct) {
          $product = db()
            ->select("products")
            ->find($reservedProduct[auth()->config("id.key")]);

          $stmt = auth()
            ->user()
            ->reservation_details()
            ->create([
              auth()->config("id.key") => uniqid(),
              "reservation_id" => $reservation[auth()->config("id.key")],
              "product_id" => $product[auth()->config("id.key")],
              "quantity" => $reservedProduct["quantity"],
            ]);

          if (!$stmt) {
            Flash::set(db()->errors());
            Flight::redirect(Flight::request()->referrer);

            exit();
          }
        }

        Flight::redirect(
          Flight::getUrl("ecommerce.confirmation.@id", [
            "id" => $reservation[auth()->config("id.key")],
          ]),
        );
      } catch (Throwable $throwable) {
        Flash::set([$throwable->getMessage()]);
        Flight::redirect(Flight::request()->referrer);

        exit();
      }
    })
      ->addMiddleware(AUTHENTICATE)
      ->addMiddleware(AUTHORIZE)
      ->setAlias("ecommerce.reservations");

    Flight::group(
      "/login",
      static function (): void {
        Flight::route("GET /", static function (): void {
          Flight::render("login", ["message" => Flash::display()]);
        })->setAlias("ecommerce.login");

        Flight::route("POST /", static function (): void {
          $data = Flight::request()->data;

          if ($data["remember"]) {
            auth()->config("session.lifetime", 0);
          }

          if (
            auth()->login([
              "email" => $data["email"],
              auth()->config("password.key") => $data["password"],
            ])
          ) {
            Flight::redirect("/ecommerce");

            return;
          }

          Flash::set(auth()->errors());
          Flight::redirect(Flight::getUrl("ecommerce.login"));
        });
      },
      [REDIRECT_IF_AUTHENTICATED],
    );

    Flight::group(
      "/register",
      static function (): void {
        Flight::route("GET /", static function (): void {
          Flight::render("register", ["message" => Flash::display()]);
        })->setAlias("ecommerce.register");

        Flight::route("POST /", static function (): void {
          $data = Flight::request()->data;

          if (
            auth()->register([
              auth()->config("id.key") => uniqid(),
              "email" => $data["email"],
              auth()->config("password.key") => $data["password"],
              auth()->config("roles.key") => json_encode(["Client"]),
            ])
          ) {
            Flight::redirect("/ecommerce");

            return;
          }

          Flash::set(auth()->errors());
          Flight::redirect("/ecommerce/register");
        });
      },
      [REDIRECT_IF_AUTHENTICATED],
    );

    Flight::route("GET /logout", static function (): void {
      auth()->logout();
      Flight::redirect(Flight::request()->referrer);
    })->setAlias("ecommerce.logout");

    Flight::route("GET /oauth2/google", static function (): void {
      try {
        $query = Flight::request()->query;
        $error = $query["error"];
        $code = $query["code"];
        $state = $query["state"];
        $flashState = Flash::display();
        $savedReferrer = Flash::displaySaved();

        if (!$savedReferrer) {
          Flash::save(Flight::request()->referrer);
        }

        $google = new Google(
          [
            "clientId" => $_ENV["GOOGLE_AUTH_CLIENT_ID"],
            "clientSecret" => $_ENV["GOOGLE_AUTH_CLIENT_SECRET"],
            "redirectUri" => explode("?", Flight::request()->getFullUrl())[0],
          ],
          ["httpClient" => new Client(["verify" => false])],
        );

        auth()->withProvider("google", $google);

        if ($error) {
          throw new Error(strval($error));
        }

        if (!$code) {
          $authorizationUrl = $google->getAuthorizationUrl();

          Flash::set($google->getState());
          Flight::redirect($authorizationUrl);

          exit();
        }

        if (!$state || $state !== $flashState) {
          throw new Error("Estado inválido");
        }

        $accessToken = $google->getAccessToken(
          "authorization_code",
          compact("code"),
        );

        if (!$accessToken instanceof AccessToken) {
          throw new Error("Código inválido");
        }

        $resourceOwner = $google->getResourceOwner($accessToken);

        if (!$resourceOwner instanceof GoogleUser) {
          throw new Error("Token inválido");
        }

        if (
          !auth()->fromOAuth([
            "token" => $accessToken,
            "user" => [
              auth()->config("id.key") => $resourceOwner->getId(),
              "email" => $resourceOwner->getEmail(),
              auth()->config("roles.key") => json_encode(["Client"]),
              "avatar" => file_get_contents($resourceOwner->getAvatar() ?: ""),
              "name" => $resourceOwner->getFirstName(),
              "last_name" => $resourceOwner->getLastName(),
            ],
          ])
        ) {
          Flash::set(auth()->errors());
          Flight::redirect($savedReferrer);
        } else {
          Flight::redirect(Flight::getUrl("ecommerce.index"));
        }
      } catch (Throwable $throwable) {
        Flash::set([$throwable->getMessage()]);
        Flight::redirect($savedReferrer);
      } finally {
        Flash::clearSaved();
      }
    })->setAlias("ecommerce.oauth2.google");

    Flight::route("GET /(@page)", static function (?string $page): void {
      Flight::render($page ?? "index", ["message" => Flash::display()]);
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
  ],
);
