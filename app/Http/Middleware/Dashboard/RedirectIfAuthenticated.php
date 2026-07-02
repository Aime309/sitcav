<?php

declare(strict_types=1);

namespace App\Http\Middleware\Dashboard;

use App\Http\Middleware\BeforeMiddleware;
use App\Http\Middleware\Middleware;
use Flight;
use Override;

final readonly class RedirectIfAuthenticated extends Middleware implements BeforeMiddleware
{
  #[Override]
  public function before(array $params = []): void
  {
    if (auth()->user()) {
      Flight::redirect('/dashboard');

      exit;
    }
  }
}
