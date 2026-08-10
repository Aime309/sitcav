<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Reserva;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ReservaController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $usuarioId = $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'];
        $usuario = Cliente::query()->findOrFail($usuarioId);

        return view('paginas.ecommerce.reservas', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        return to_route('{negocio}.reservas.{reserva}', [
            'negocio' => $negocio,
            'reserva' => uniqid(),
        ]);
    }

    public function show(Negocio $negocio, Reserva $reserva): View
    {
        $usuarioId = $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'];
        $usuario = Cliente::query()->findOrFail($usuarioId);

        return view('paginas.ecommerce.reserva', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'reserva' => $reserva,
        ]);
    }

    public function update(
        Negocio $negocio,
        Reserva $reserva,
    ): RedirectResponse {
        return to_route('{negocio}.reservas', ['negocio' => $negocio]);
    }
}
