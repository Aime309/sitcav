<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ClienteController extends Controller
{
    public function create(Request $request, Negocio $negocio): View
    {
        return view('{negocio}_registrarse', ['negocio' => $negocio]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        $cliente = $negocio->clientes()->create([
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'clave' => password_hash($_POST['clave'] ?? '', PASSWORD_DEFAULT),
            'telefono' => $_POST['telefono'] ?? '',
        ]);

        session_start();
        $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] = $cliente->id;

        return to_route('{negocio}', [
            'negocio' => $negocio['slug'],
        ]);
    }

    public function edit(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id']);

        return view('{negocio}_perfil', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, Negocio $negocio): RedirectResponse
    {
        session_start();
        $cliente = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id']);

        $cliente->nombre = $_POST['nombre'] ?? $cliente->nombre;
        $cliente->apellido = $_POST['apellido'] ?? $cliente->apellido;
        $cliente->correo = $_POST['correo'] ?? $cliente->correo;
        $cliente->telefono = $_POST['telefono'] ?? $cliente->telefono;

        if (!empty($_POST['clave'])) {
            $cliente->clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
        }

        $cliente->save();

        return to_route('{negocio}.perfil', ['negocio' => $negocio]);
    }
}
