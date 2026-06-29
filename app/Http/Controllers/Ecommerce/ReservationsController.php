<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Error;
use Flight;
use Throwable;

final readonly class ReservationsController extends Controller
{
  public function create(): void
  {
    try {
      $data = Flight::request()->data;

      if (!$data["selector"]) {
        throw new Error("Por favor acepta los términos y condiciones");
      }

      if (!auth()->update($data["user"])) {
        flash()->set(auth()->errors());
        Flight::redirect(Flight::request()->referrer);

        exit;
      }

      $reservation["id"] = uniqid();

      if (!auth()->user()->reservations()->create($reservation)) {
        flash()->set(db()->errors());
        Flight::redirect(Flight::request()->referrer);

        exit;
      }

      foreach ($data["reservedProducts"] as $reservedProduct) {
        $product = db()
          ->select("products")
          ->find($reservedProduct["id"]);

        $stmt = auth()
          ->user()
          ->reservation_details()
          ->create([
            "id" => uniqid(),
            "reservation_id" => $reservation["id"],
            "product_id" => $product["id"],
            "quantity" => $reservedProduct["quantity"],
          ]);

        if (!$stmt) {
          flash()->set(db()->errors());
          Flight::redirect(Flight::request()->referrer);

          exit;
        }
      }

      Flight::redirect(
        Flight::getUrl("ecommerce.confirmation.@id", [
          "id" => $reservation["id"],
        ]),
      );
    } catch (Throwable $throwable) {
      flash()->set([$throwable->getMessage()]);
      Flight::redirect(Flight::request()->referrer);

      exit;
    }
  }
}
