<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

final class IniciarSesion extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $usuario = Usuario::query()->where('correo', $correo)->firstOrFail();

        if (password_verify($clave, $usuario->clave)) {
            session_start();
            $_SESSION['panel']['usuario']['id'] = $usuario->id;

            if ($usuario->roles->contains('rol', 'administrador')) {
                return to_route('panel.negocios');
            }

            if ($usuario->negocios->count()) {
                return to_route('panel.negocios.{negocio}', [
                    'negocio' => $usuario->negocios[0],
                ]);
            }

            return to_route('panel.negocios.{negocio}.sucursales.{sucursal}', [
                'negocio' => $usuario->sucursales[0]->negocio->id,
                'sucursal' => $usuario->sucursales[0]->id,
            ]);
        }

        return to_route('panel.iniciar-sesion');
    }
}
