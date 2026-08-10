<?php

declare(strict_types=1);

namespace App\Http\Middleware\Panel;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class SoloEstablecimientosAsignados
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);
        $negocio = $request->route('negocio');
        $sucursal = $request->route('sucursal');

        if (
            $usuario->sucursales->contains($sucursal)
            || $usuario->negocios->contains($negocio)
        ) {
            return $next($request);
        }

        return abort(403);
    }
}
