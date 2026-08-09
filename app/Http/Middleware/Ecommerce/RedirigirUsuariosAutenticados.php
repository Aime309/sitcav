<?php

declare(strict_types=1);

namespace App\Http\Middleware\Ecommerce;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class RedirigirUsuariosAutenticados
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $negocio = $request->route('negocio');

        if (empty($_SESSION['ecommerce'][$negocio->slug]['usuario']['id'])) {
            return $next($request);
        }

        return redirect($_SERVER['REQUEST_URI']);
    }
}
