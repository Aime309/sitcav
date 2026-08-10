<?php

declare(strict_types=1);

namespace App\Http\Middleware\Panel;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class SoloAdministradores
{
    /** @param callable(Request): Response $next */
    public function handle(Request $request, callable $next): Response
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        if ($usuario->roles->contains('rol', 'administrador')) {
            return $next($request);
        }

        return redirect($_SERVER['HTTP_REFERER']);
    }
}
