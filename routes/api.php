<?php

declare(strict_types=1);

use Leaf\Exception\Handler\JsonResponseHandler;
use Leaf\Exception\Run;

Flight::group(
  "/api",
  static function (): void {
    Flight::route('POST /login', static function (): void {
      $data = Flight::request()->data;

      if (auth()->login(['email' => $data['email'], 'password' => $data['password']])) {
        Flight::json(auth()->user()->getAuthInfo());

        return;
      }

      Flight::json(auth()->errors(), 401);
    });

    Flight::route("GET /products/types", static function (): void {
      Flight::json(
        array_filter(
          array_map(
            static fn(array $product): string => $product["type"],
            db()->select("products", "DISTINCT type")->all(),
          ),
        ),
      );
    })->setAlias("api.products.types");

    Flight::route("GET /products/categories", static function (): void {
      Flight::json(
        array_filter(
          array_map(
            static fn(array $product): string => $product["category"],
            db()->select("products", "DISTINCT category")->all(),
          ),
        ),
      );
    })->setAlias("api.products.categories");

    Flight::route("GET /products", static function (): void {
      Flight::json(
        array_map(
          static fn(array $product): array => [
            "sources" => json_decode($product["sources"]),
            "pinned" => (bool) $product["pinned"],
          ] + $product,
          db()->select("products")->all(),
        ),
      );
    })->setAlias("api.products");

    Flight::route("GET /products/pinned", static function (): void {
      Flight::json(
        array_map(
          static fn(array $product): array => [
            "src" => json_decode($product["sources"])[0],
            "pinned" => (bool) $product["pinned"],
          ] + $product,
          db()->select("products")->where("pinned", true)->all(),
        ),
      );
    })->setAlias("api.products.pinned");

    Flight::route("GET /products/trending", static function (): void {
      Flight::json([
        [
          "src" => "./img/tranding_item/tranding_item_1.png",
          "name" => "Cervical pillow for airplane car office nap pillow",
          "price" => 5,
        ],
        [
          "src" => "./img/tranding_item/tranding_item_2.png",
          "name" => "Foam filling cotton slow rebound pillows",
          "price" => 5,
        ],
        [
          "src" => "./img/tranding_item/tranding_item_3.png",
          "name" => "Memory foam filling cotton Slow rebound pillows",
          "price" => 5,
        ],
        [
          "src" => "./img/tranding_item/tranding_item_4.png",
          "name" => "Cervical pillow for airplane car office nap pillow",
          "price" => 5,
        ],
        [
          "src" => "./img/tranding_item/tranding_item_5.png",
          "name" => "Foam filling cotton slow rebound pillows",
          "price" => 5,
        ],
        [
          "src" => "./img/tranding_item/tranding_item_6.png",
          "name" => "Memory foam filling cotton Slow rebound pillows",
          "price" => 5,
        ],
      ]);
    })->setAlias("api.products.trending");

    Flight::route("GET /products/@" . auth()->config("id.key"), static function (string $id): void {
      $product = db()->select("products")->find($id);
      $product["sources"] = json_decode($product["sources"]);
      $product["stock"] = 10;

      Flight::json($product);
    })->setAlias("api.products.@" . auth()->config("id.key"));
  },
  [
    static function (): void {
      $run = new Run();
      $run->pushHandler(new JsonResponseHandler())->register();
    },
  ],
);
