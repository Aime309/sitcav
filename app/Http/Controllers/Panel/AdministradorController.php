<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class AdministradorController extends Controller
{
    public function create(Request $request): View
    {
        return view('panel_registrarse');
    }

    public function store(Request $request): RedirectResponse
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

            session_start();
            $_SESSION['panel']['usuario']['id'] = $usuario->id;
        });

        return to_route('panel.negocios');
    }
}
