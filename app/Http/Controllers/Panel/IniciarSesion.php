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
            $usuario['asignacion'] = (array) (DB::select(
                'SELECT * FROM asignaciones WHERE usuario_id = ?',
                [$usuario->id],
            )[0] ?? new stdClass);

            session_start();
            $_SESSION['panel']['usuario']['id'] = $usuario->id;

            if ($usuario->roles->contains('rol', 'administrador')) {
                return to_route('panel.negocios');
            }

            if ($usuario['asignacion']) {
                if ($usuario['asignacion']['negocio_id']) {
                    return to_route('panel.negocios.{negocio}', [
                        'negocio' => $usuario['asignacion']['negocio_id'],
                    ]);
                }

                $sucursal = Sucursal::query()->find($usuario['asignacion']['sucursal_id']);

                return to_route('panel.negocios.{negocio}.sucursales.{sucursal}', [
                    'negocio' => $sucursal->negocio->id,
                    'sucursal' => $sucursal->id,
                ]);
            }
        }

        return to_route('panel.iniciar-sesion');
    }
}
