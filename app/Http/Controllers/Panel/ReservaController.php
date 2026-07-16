<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class ReservaController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('panel_negocios_{negocio}_reservas', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }
}
