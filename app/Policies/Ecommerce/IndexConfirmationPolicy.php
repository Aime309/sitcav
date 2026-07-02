<?php

declare(strict_types=1);

namespace App\Policies\Ecommerce;

use App\Http\Middleware\BeforeMiddleware;
use App\Http\Middleware\Middleware;
use Flight;
use Override;

final readonly class IndexConfirmationPolicy extends Middleware implements BeforeMiddleware
{
  #[Override]
  public function before(array $params = []): void
  {
    $reservation = db()
      ->select("reservations")
      ->find($params['id'] ?? '');

    if (!$reservation) {
      Flight::redirect(Flight::getUrl("ecommerce.index"));

      exit;
    }

    if ($reservation["user_id"] !== auth()->id()) {
      Flight::redirect(Flight::getUrl("ecommerce.index"));

      exit;
    }
  }
}
