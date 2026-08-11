<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\RedirectResponse;

final class CerrarSesion extends Controller
{
    public function __invoke(Negocio $negocio): RedirectResponse {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['ecommerce'][$negocio->slug]);

        return to_route('{negocio}', ['negocio' => $negocio]);
    }
}
