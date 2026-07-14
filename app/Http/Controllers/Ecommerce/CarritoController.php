<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class CarritoController extends Controller
{
    public function index(Negocio $negocio): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_carrito', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Negocio $negocio, ?Producto $producto = null): RedirectResponse
    {
        return to_route('{negocio}.carrito', ['negocio' => $negocio]);
    }
}
