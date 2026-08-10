<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;

final class ReservaController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.reservas', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }
}
