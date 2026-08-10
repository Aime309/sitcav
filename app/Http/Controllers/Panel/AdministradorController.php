<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class AdministradorController extends Controller
{
    public function create(): View
    {
        return view('paginas.panel.registrarse');
    }

    public function store(): RedirectResponse
    {
        DB::transaction(static function (): void {
            $usuario = Usuario::query()->create([
                'correo' => $_POST['correo'] ?? '',
                'clave' => password_hash(
                    $_POST['clave'] ?? '',
                    PASSWORD_DEFAULT,
                ),
            ]);

            $usuario->roles()->createMany([
                ['rol' => 'administrador'],
                ['rol' => 'encargado'],
                ['rol' => 'vendedor'],
            ]);

            $_SESSION['panel']['usuario']['correo'] = $usuario->correo;
        });

        return to_route('panel.negocios');
    }
}
