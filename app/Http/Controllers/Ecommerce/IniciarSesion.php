<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class IniciarSesion extends Controller
{
    public function __invoke(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse {
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';

        $usuario = Cliente::query()->where('correo', $correo)->firstOrFail();

        if ($usuario && password_verify($clave, $usuario['clave'])) {
            session_start();
            $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] = $usuario->id;

            return to_route('{negocio}', ['negocio' => $negocio]);
        }

        return to_route('{negocio}.iniciar-sesion', [
            'negocio' => $negocio,
        ]);
    }
}
