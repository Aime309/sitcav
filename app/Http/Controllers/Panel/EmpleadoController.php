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
    public function index(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $empleados = [];

        foreach ($negocio->empleados as $empleado) {
            $empleados[] = $empleado;
        }

        foreach ($negocio->sucursales as $sucursal) {
            foreach ($sucursal->empleados as $empleado) {
                $empleados[] = $empleado;
            }
        }

        foreach ($empleados as $empleado) {
            $empleado['asignaciones'] = PDO
                ->query("
                    SELECT * FROM asignaciones
                    WHERE usuario_id = '{$empleado['id']}'
                ")
                ->fetchAll();
        }

        return view('panel_negocios_{negocio}_empleados', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'empleados' => $empleados,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        $establecimiento = $_POST['establecimiento'] ?? '';
        $negocio = Negocio::query()->find($establecimiento);
        $sucursal = Sucursal::query()->find($establecimiento);

        DB::transaction(static function () use ($negocio, $sucursal): void {
            $empleado = Usuario::query()->create([
                'correo' => $_POST['correo'] ?? '',
                'clave' => password_hash($_POST['clave'] ?? '', PASSWORD_DEFAULT),
            ]);

            switch ($_POST['rol'] ?? '') {
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
                INSERT INTO asignaciones
                (id, usuario_id, negocio_id, sucursal_id) VALUES
                (:id, :usuario_id, :negocio_id, :sucursal_id)
            ', [
                ':id' => uniqid(),
                ':usuario_id' => $empleado->id,
                ':negocio_id' => $negocio?->id,
                ':sucursal_id' => $sucursal?->id,
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
        DB::transaction(static function () use ($empleado): void {
            $empleado->roles->each(static fn(UsuarioRol $rol) => $rol->delete());

            switch ($_POST['rol'] ?? '') {
                case 'encargado':
                    $empleado->roles()->create(['rol' => 'encargado']);
                    $empleado->roles()->create(['rol' => 'vendedor']);

                    break;
                default:
                    $empleado->roles()->create(['rol' => 'vendedor']);
            }

            $empleado->save();

            DB::update('
                UPDATE asignaciones SET
                negocio_id = :negocio_id,
                sucursal_id = :sucursal_id,
                actualizado_en = CURRENT_TIMESTAMP
                WHERE usuario_id = :usuario_id
            ', [
                ':negocio_id' => Negocio::query()->find($_POST['establecimiento'] ?? null)?->id,
                ':sucursal_id' => Sucursal::query()->find($_POST['establecimiento'] ?? null)?->id,
                ':usuario_id' => $empleado->id,
            ]);
        });

        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio,
        ]);
    }
}
