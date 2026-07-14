<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class PerfilController extends Controller
{
    public function edit(Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_perfil', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Negocio $negocio): RedirectResponse
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        $usuario->nombre = $_POST['nombre'] ?? $usuario->nombre;
        $usuario->apellido = $_POST['apellido'] ?? $usuario->apellido;
        $usuario->correo = $_POST['correo'] ?? $usuario->correo;
        $usuario->telefono = $_POST['telefono'] ?? $usuario->telefono;

        if (!empty($_POST['clave'])) {
            $usuario->clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
        }

        $usuario->save();

        return to_route('panel.negocios.{negocio}.perfil', [
            'negocio' => $negocio,
        ]);
    }
}
