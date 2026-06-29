<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductsController;
use Leaf\Exception\Handler\JsonResponseHandler;
use Leaf\Exception\Run;

Flight::group(
  '/api',
  static function (): void {
    Flight::route('POST /login', static function (): void {
      $data = Flight::request()->data;

      if (auth()->login(['email' => $data['email'], 'password' => $data['password']])) {
        Flight::json(auth()->user()->getAuthInfo());

        return;
      }

      Flight::json(auth()->errors(), 401);
    });

    Flight::router()->get('/products/types', [ProductsController::class, 'indexTypes'])->setAlias('api.products.types');
    Flight::router()->get('/products/categories', [ProductsController::class, 'indexCategories'])->setAlias('api.products.categories');
    Flight::router()->get('/products', [ProductsController::class, 'index'])->setAlias('api.products');
    Flight::router()->get('/products/pinned', [ProductsController::class, 'indexPinned'])->setAlias('api.products.pinned');
    Flight::router()->get('/products/trending', [ProductsController::class, 'indexTrending'])->setAlias('api.products.trending');
    Flight::router()->get('/products/@id', [ProductsController::class, 'show'])->setAlias('api.products.@id');
  },
  [
    static function (): void {
      $run = new Run;
      $run->pushHandler(new JsonResponseHandler)->register();
    },
  ],
);
