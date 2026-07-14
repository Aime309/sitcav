<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class ProductoController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function show(Request $request, Negocio $negocio, Producto $producto): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_productos_{producto}', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    }
}
