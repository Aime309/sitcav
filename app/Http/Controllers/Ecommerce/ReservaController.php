<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Reserva;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ReservaController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_reservas', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        return to_route('{negocio}.reservas.{reserva}', [
            'negocio' => $negocio,
            'reserva' => uniqid(),
        ]);
    }

    public function show(Request $request, Negocio $negocio, Reserva $reserva): View
    {
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_reservas_{reserva}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'reserva' => $reserva,
        ]);
    }

    public function update(Request $request, Negocio $negocio, Reserva $reserva): RedirectResponse
    {
        return to_route('{negocio}.reservas', ['negocio' => $negocio]);
    }
}
