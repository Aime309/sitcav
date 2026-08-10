<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class IniciarSesion extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        ['correo' => $correo, 'clave' => $clave] = $request->validate([
            'correo' => 'email',
            'clave' => 'required',
        ], [
            'correo.email' => 'El correo electrónico no es válido.',
            'clave.required' => 'La contraseña es obligatoria.',
        ]);

        $usuario = Usuario::query()
            ->where('correo', $correo)
            ->firstOrFail();

        if (password_verify($clave, $usuario->clave)) {
            $_SESSION['panel']['usuario']['correo'] = $usuario->correo;

            return to_route('panel.negocios');
        }

        return to_route('panel.iniciar-sesion');
    }
}
