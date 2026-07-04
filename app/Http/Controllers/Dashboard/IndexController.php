<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Flight;

final readonly class IndexController extends Controller
{
  public function __invoke(): void
  {
    $clients = db()
      ->query('SELECT COUNT(' . auth()->config('id.key') . ') FROM ' . auth()->config('db.table') . ' WHERE ' . auth()->config('roles.key') . ' LIKE "%Client%"')
      ->column();

    $revenue = 0;
    $returns = 0;
    $conversionRatePercentage = 0;
    $currentYearReservationsPerMonth = array_map(static fn(): int => rand(1, 100), range(1, 12));
    $reservationsPerYear = [date('Y') - 1 => 0, date('Y') - 2 => 0, date('Y') => 0];
    $reservationSales = 0;
    $currentMonthReservationSalesPerDay = array_map(static fn(): int => rand(1, 10), range(0, date('t')));
    $reservationSalesPerMonth = [date('n') => 0, date('n') - 1 => 0, date('n') - 2 => 0];
    $currentYearSales = 0;
    $currentYearSalesPerMonth = array_map(static fn(): int => rand(1, 100), range(1, 12));
    $currentYearSalesAverage = 0;
    $currentYearSalesAveragePerMonth = array_map(static fn(): int => rand(1, 100), range(1, 12));

    $customerSatisfaction = [
      'extremely satisfied' => rand(1, 100),
      'satisfied' => rand(1, 100),
      'poor' => rand(1, 100),
      'very poor' => rand(1, 100),
    ];

    $newProducts = array_map(static function (array $product): array {
      $product['sources'] = json_decode($product['sources']);

      return $product;
    }, db()->select('products')->orderBy('id')->limit(4)->all());

    $totalProfit = 0;
    $totalReservations = 0;
    $averagePrice = 0;
    $productSold = 0;

    Flight::render('index', compact(
      'clients',
      'revenue',
      'returns',
      'conversionRatePercentage',
      'currentYearReservationsPerMonth',
      'reservationsPerYear',
      'reservationSales',
      'currentMonthReservationSalesPerDay',
      'reservationSalesPerMonth',
      'currentYearSales',
      'currentYearSalesPerMonth',
      'currentYearSalesAverage',
      'currentYearSalesAveragePerMonth',
      'customerSatisfaction',
      'newProducts',
      'totalProfit',
      'totalReservations',
      'averagePrice',
      'productSold',
    ));
  }
}
