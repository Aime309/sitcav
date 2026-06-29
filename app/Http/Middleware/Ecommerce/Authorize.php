<?php

declare(strict_types=1);

namespace App\Http\Middleware\Ecommerce;

use App\Http\Middleware\BeforeMiddleware;
use App\Http\Middleware\Middleware;
use Flight;
use Override;

final readonly class Authorize extends Middleware implements BeforeMiddleware
{
  #[Override]
  public static function before(array $params = []): void
  {
    if (!auth()->user()?->is("Client")) {
      Flight::redirect(Flight::request()->referrer);

      exit();
    }
  }
}
