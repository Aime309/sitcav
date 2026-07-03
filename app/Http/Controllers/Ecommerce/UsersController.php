<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Flight;

final readonly class UsersController extends Controller
{
  public function create(): void
  {
    $data = Flight::request()->data;

    if (
      auth()->register([
        auth()->config("id.key") => uniqid(),
        "email" => $data["email"],
        auth()->config("password.key") => $data["password"],
        auth()->config("roles.key") => json_encode(["Client"]),
      ])
    ) {
      Flight::redirect(Flight::getUrl('ecommerce.index'));

      return;
    }

    flash()->set(auth()->errors());
    Flight::redirect(Flight::getUrl('ecommerce.register'));
  }
}
