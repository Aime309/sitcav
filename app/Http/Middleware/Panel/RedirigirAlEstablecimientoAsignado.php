<?php

declare(strict_types=1);

namespace App\Http\Middleware\Panel;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class RedirigirAlEstablecimientoAsignado
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response
    {
        $usuarioId = $_SESSION['panel']['usuario']['id'];
        $usuario = Usuario::query()->findOrFail($usuarioId);

        if ($usuario->roles->contains('rol', 'administrador')) {
            return $next($request);
        }

        if ($usuario->negocios->count()) {
            return str_ends_with(
                route('panel.negocios.{negocio}', [
                    'negocio' => $usuario->negocios[0],
                ]),
                $_SERVER['REQUEST_URI'],
            )
                ? $next($request)
                : to_route('panel.negocios.{negocio}', [
                    'negocio' => $usuario->negocios[0],
                ]);
        }

        if ($usuario->sucursales->count()) {
            return str_ends_with(
                route('panel.negocios.{negocio}.sucursales.{sucursal}', [
                    'negocio' => $usuario->sucursales[0]->negocio,
                    'sucursal' => $usuario->sucursales[0],
                ]),
                $_SERVER['REQUEST_URI'],
            )
                ? $next($request)
                : to_route('panel.negocios.{negocio}.sucursales.{sucursal}', [
                    'negocio' => $usuario->sucursales[0]->negocio,
                    'sucursal' => $usuario->sucursales[0],
                ]);
        }

        return redirect($_SERVER['HTTP_REFERER']);
    }
}
