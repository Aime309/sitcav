<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ClienteController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.clientes', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        return to_route('panel_negocios_{negocio}_clientes', [
            'negocio' => $negocio,
        ]);
    }

    public function update(
        Negocio $negocio,
        Cliente $cliente,
    ): RedirectResponse {
        return to_route('panel_negocios_{negocio}_clientes', [
            'negocio' => $negocio,
        ]);
    }
}
