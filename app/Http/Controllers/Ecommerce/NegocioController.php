<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use Illuminate\Contracts\View\View;

final class NegocioController extends Controller
{
    public function show(Negocio $negocio): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }
}
