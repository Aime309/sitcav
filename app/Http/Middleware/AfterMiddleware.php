<?php

declare(strict_types=1);

namespace App\Http\Middleware;

interface AfterMiddleware
{
  public static function after(array $params = []): void;
}
