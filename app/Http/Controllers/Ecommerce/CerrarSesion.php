<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CerrarSesion extends Controller
{
    public function __invoke(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse {
        session_start();
        unset($_SESSION['ecommerce'][$negocio->slug]);

        return to_route('{negocio}', ['negocio' => $negocio]);
    }
}
