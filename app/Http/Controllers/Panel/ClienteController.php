<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ClienteController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.clientes', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse
    {
        [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'clave' => $clave,
            'telefono' => $telefono,
        ] = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'email',
            'clave' => 'required|min:8',
            'telefono' => 'required',
        ]);

        $negocio->clientes()->create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'clave' => password_hash($clave, PASSWORD_DEFAULT),
            'telefono' => $telefono,
        ]);

        return to_route('panel.negocios.{negocio}.clientes', [
            'negocio' => $negocio,
        ]);
    }

    public function edit(Negocio $negocio, Cliente $cliente): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.editar-cliente', [
            'negocio' => $negocio,
            'cliente' => $cliente,
            'usuario' => $usuario,
        ]);
    }

    public function update(
        Request $request,
        Negocio $negocio,
        Cliente $cliente,
    ): RedirectResponse {
        [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'telefono' => $telefono,
        ] = $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correo' => 'email',
            'telefono' => 'required',
        ]);

        $cliente->update([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'telefono' => $telefono,
        ]);

        return to_route('panel.negocios.{negocio}.clientes', [
            'negocio' => $negocio,
        ]);
    }
}
