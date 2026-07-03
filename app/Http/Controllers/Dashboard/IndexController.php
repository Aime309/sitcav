<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Flight;

final readonly class IndexController extends Controller
{
  public function __invoke(): void
  {
    Flight::render('index');
  }
}
