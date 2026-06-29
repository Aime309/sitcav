<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Flight;

final readonly class ConfirmationController extends Controller
{
  public function renderConfirmationById(string $id): void
  {
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

      $details["product"]["sources"] = json_decode($details["product"]["sources"]);
      $reservation["total"] += $details["quantity"] * $details["product"]["price"];
    }

    Flight::render("confirmation", compact("reservation"));
  }
}
