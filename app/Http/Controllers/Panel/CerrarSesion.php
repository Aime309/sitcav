<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

final class CerrarSesion extends Controller
{
    public function __invoke(): RedirectResponse
    {
        session_start();
        unset($_SESSION['panel']);

        return to_route('panel.iniciar-sesion');
    }
}
