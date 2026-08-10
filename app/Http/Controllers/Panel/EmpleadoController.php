<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class EmpleadoController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);
        $empleados = [];

        foreach ($negocio->empleados as $empleado) {
            if ($empleado->is($usuario)) {
                continue;
            }

            $empleados[] = $empleado;
        }

        foreach ($negocio->sucursales as $sucursal) {
            foreach ($sucursal->empleados as $empleado) {
                if ($empleado->is($usuario)) {
                    continue;
                }

                $empleados[] = $empleado;
            }
        }

        return view('paginas.panel.empleados', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'empleados' => $empleados,
        ]);
    }

    public function store(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse {
        [
            'establecimiento' => $establecimiento,
            'correo' => $correo,
            'clave' => $clave,
            'rol' => $rol,
        ] = $request->validate([
            'establecimiento' => 'required',
            'correo' => 'email|unique:usuarios',
            'clave' => 'required|min:8',
            'rol' => 'required',
        ]);

        $negocio = Negocio::query()->find($establecimiento);
        $sucursal = Sucursal::query()->find($establecimiento);

        DB::transaction(static function () use (
            $negocio,
            $sucursal,
            $correo,
            $clave,
            $rol,
        ): void {
            $empleado = Usuario::query()->create([
                'correo' => $correo,
                'clave' => password_hash($clave, PASSWORD_DEFAULT),
            ]);

            switch ($rol) {
                case 'encargado':
                    $empleado->roles()->createMany([
                        ['rol' => 'encargado'],
                        ['rol' => 'vendedor'],
                    ]);

                    break;
                default:
                    $empleado->roles()->create(['rol' => 'vendedor']);
            }

            DB::insert('
                INSERT INTO usuarios_establecimientos
                (usuario_correo, negocio_slug, sucursal_slug) VALUES
                (:usuario_correo, :negocio_slug, :sucursal_slug)
            ', [
                ':usuario_correo' => $empleado->correo,
                ':negocio_slug' => $negocio?->slug,
                ':sucursal_slug' => $sucursal?->slug,
            ]);
        });

        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio ?: $sucursal->negocio,
        ]);
    }

    public function update(
        Request $request,
        Negocio $negocio,
        Usuario $empleado,
    ): RedirectResponse {
        [
            'establecimiento' => $establecimiento,
            'rol' => $rol,
        ] = $request->validate([
            'establecimiento' => 'required',
            'rol' => 'required',
        ]);

        DB::transaction(static function () use (
            $empleado,
            $establecimiento,
            $rol,
        ): void {
            $empleado
                ->roles
                ->each(static fn(UsuarioRol $rol): ?bool => $rol->delete());

            $negocio = Negocio::query()->find($establecimiento);
            $sucursal = Sucursal::query()->find($establecimiento);

            switch ($rol) {
                case 'encargado':
                    $empleado->roles()->createMany([
                        ['rol' => 'encargado'],
                        ['rol' => 'vendedor'],
                    ]);

                    break;
                default:
                    $empleado->roles()->create(['rol' => 'vendedor']);
            }

            $empleado->save();

            DB::update('
                UPDATE usuarios_establecimientos SET
                negocio_slug = :negocio_slug,
                sucursal_slug = :sucursal_slug
                WHERE usuario_correo = :usuario_correo
            ', [
                ':negocio_slug' => $negocio?->slug,
                ':sucursal_slug' => $sucursal?->slug,
                ':usuario_correo' => $empleado->correo,
            ]);
        });

        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio,
        ]);
    }
}
