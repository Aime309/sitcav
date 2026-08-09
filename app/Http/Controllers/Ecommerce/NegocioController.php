<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class NegocioController extends Controller
{
    public function show(Request $request, Negocio $negocio): View
    {
        $usuarioId = $_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null;
        $usuario = Cliente::query()->find($usuarioId);

        return view('paginas.ecommerce.inicio', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }
}
