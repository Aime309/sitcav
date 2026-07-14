<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class VentaController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_ventas', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio, ?Reserva $reserva = null): RedirectResponse
    {
        return to_route('panel.negocios.{negocio}.ventas', [
            'negocio' => $negocio,
        ]);
    }
}
