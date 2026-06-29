<?php

declare(strict_types=1);

namespace App\Http\Middleware;

interface BeforeMiddleware
{
  public static function before(array $params = []): void;
}
