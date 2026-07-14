<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ClienteController extends Controller
{
    public function create(Negocio $negocio): View
    {
        return view('{negocio}_registrarse', ['negocio' => $negocio]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $imagenes = [];

        $cliente = new Cliente;
        $cliente->id = uniqid();
        $cliente->nombre = $nombre;
        $cliente->apellido = $apellido;
        $cliente->correo = $correo;
        $cliente->clave = password_hash($clave, PASSWORD_DEFAULT);
        $cliente->telefono = $telefono;
        $cliente->imagenes = json_encode($imagenes);
        $cliente->save();

        return to_route('{negocio}.iniciar-sesion', [
            'negocio' => $negocio['slug'],
        ]);
    }

    public function edit(Negocio $negocio): View
    {
        session_start();
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('{negocio}_perfil', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Negocio $negocio): RedirectResponse
    {
        session_start();
        $cliente = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);
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
