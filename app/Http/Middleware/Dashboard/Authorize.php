<?php

declare(strict_types=1);

namespace App\Http\Middleware\Dashboard;

use App\Http\Middleware\BeforeMiddleware;
use App\Http\Middleware\Middleware;
use Flight;
use Override;

final readonly class Authorize extends Middleware implements BeforeMiddleware
{
  public function __construct(private array $roles = []) {}

  #[Override]
  public function before(array $params = []): void
  {
    if (auth()->user()->is('Client')) {
      Flight::redirect(Flight::getUrl('dashboard.logout'));

      exit;
    }

    if ($this->roles && auth()->user()->isNot($this->roles)) {
      Flight::redirect(Flight::request()->referrer);

      exit;
    }
  }
}
