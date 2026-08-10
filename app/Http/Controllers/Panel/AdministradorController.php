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
    public function create(): View
    {
        return view('paginas.panel.registrarse');
    }

    public function store(Request $request): RedirectResponse
    {
        ['correo' => $correo, 'clave' => $clave] = $request->validate([
            'correo' => 'email|unique:usuarios',
            'clave' => 'min:8',
        ], [
            'correo.email' => 'El correo electrónico no es válido.',
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'clave.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        DB::transaction(static function () use ($correo, $clave): void {
            $usuario = Usuario::query()->create([
                'correo' => $correo,
                'clave' => password_hash(
                    $clave,
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
