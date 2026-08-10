<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class CompraController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.compras', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        return to_route('panel.negocios.{negocio}.compras', [
            'negocio' => $negocio,
        ]);
    }
}
