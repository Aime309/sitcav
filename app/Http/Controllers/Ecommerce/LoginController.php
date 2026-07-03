<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Flight;

final readonly class LoginController extends Controller
{
  public function authenticate(): void
  {
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
      Flight::redirect(Flight::getUrl('ecommerce.index'));

      return;
    }

    flash()->set(auth()->errors());
    Flight::redirect(Flight::getUrl("ecommerce.login"));
  }
}
