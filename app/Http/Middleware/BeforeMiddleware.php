<?php

declare(strict_types=1);

namespace App\Http\Middleware;

interface BeforeMiddleware
{
  public function before(array $params = []): void;
}
