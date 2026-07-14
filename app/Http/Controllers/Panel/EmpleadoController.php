<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class EmpleadoController extends Controller
{
    public function index(Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);
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

            $empleado->roles = json_decode($empleado['roles'], true);
        }

        return view('panel_negocios_{negocio}_empleados', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'empleados' => $empleados,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        $rol = $_POST['rol'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $imagen = $_FILES['imagen'] ?? [];
        $establecimiento = $_POST['establecimiento'] ?? '';
        $negocio = Negocio::query()->find($establecimiento);
        $sucursal = Sucursal::query()->find($establecimiento);

        PDO->beginTransaction();

        $empleado = new Usuario;
        $empleado->id = uniqid();
        $empleado->nombre = $nombre;
        $empleado->apellido = $apellido;
        $empleado->correo = $correo;
        $empleado->clave = password_hash($clave, PASSWORD_DEFAULT);
        $empleado->telefono = $telefono;

        $empleado->roles = json_encode(match ($rol) {
            'encargado' => ['encargado', 'vendedor'],
            'vendedor' => ['vendedor'],
        });

        if ($imagen['error'] === UPLOAD_ERR_OK) {
            $empleado->imagen = fopen($imagen['tmp_name'], 'rb');
        }

        $empleado->save();

        PDO->prepare(
            'INSERT INTO asignaciones
            (id, usuario_id, negocio_id, sucursal_id) VALUES
            (:id, :usuario_id, :negocio_id, :sucursal_id)'
        )->execute([
            ':id' => uniqid(),
            ':usuario_id' => $empleado->id,
            ':negocio_id' => $negocio?->id,
            ':sucursal_id' => $sucursal?->id,
        ]);

        PDO->commit();

        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio,
        ]);
    }

    public function update(Negocio $negocio, Usuario $empleado): RedirectResponse
    {
        PDO->beginTransaction();

        $empleado->activo = ($_POST['activo'] ?? '') === 'on'
            ? 1
            : 0;

        $empleado->roles = match ($_POST['rol'] ?? '') {
            'encargado' => json_encode(['encargado', 'vendedor']),
            'vendedor' => json_encode(['vendedor']),
            default => $empleado->roles,
        };

        $empleado->save();

        PDO->prepare(
            'UPDATE asignaciones SET
            negocio_id = :negocio_id,
            sucursal_id = :sucursal_id,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE usuario_id = :usuario_id'
        )->execute([
            ':negocio_id' => Negocio::query()->find($_POST['establecimiento'] ?? null)?->id,
            ':sucursal_id' => Sucursal::query()->find($_POST['establecimiento'] ?? null)?->id,
            ':usuario_id' => $empleado->id,
        ]);

        PDO->commit();

        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio,
        ]);
    }
}
