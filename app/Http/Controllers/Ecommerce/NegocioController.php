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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $usuarioId = $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] ?? null;
        $usuario = Cliente::query()->find($usuarioId);

        return view('paginas.ecommerce.inicio', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }
}
