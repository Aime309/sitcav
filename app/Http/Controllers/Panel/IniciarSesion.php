<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;

final class IniciarSesion extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $usuario = Usuario::query()->where('correo', $correo)->firstOrFail();

        if (password_verify($clave, $usuario->clave)) {
            $_SESSION['panel']['usuario']['correo'] = $usuario->correo;

            return to_route('panel.negocios');
        }

        return to_route('panel.iniciar-sesion');
    }
}
