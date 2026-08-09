<?php

declare(strict_types=1);

namespace App\Http\Middleware\Panel;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class SoloUsuariosAutenticados
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['panel']['usuario']['id'])) {
            return to_route('panel.iniciar-sesion');
        }

        return $next($request);
    }
}
