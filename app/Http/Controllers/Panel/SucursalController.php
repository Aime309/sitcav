<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class SucursalController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.sucursales', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        DB::transaction(static function () use ($negocio): void {
            $correo = $_SESSION['panel']['usuario']['correo'];
            $usuario = Usuario::query()->findOrFail($correo);

            $usuario->sucursales()->create([
                'nombre' => $_POST['nombre'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'negocio_slug' => $negocio->slug,
            ]);
        });

        return to_route('panel.negocios.{negocio}.sucursales', [
            'negocio' => $negocio,
        ]);
    }

    public function show(Negocio $negocio, Sucursal $sucursal): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->find($correo);

        return view('paginas.panel.sucursal', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'sucursal' => $sucursal,
        ]);
    }

    public function edit(Negocio $negocio, Sucursal $sucursal): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view(
            'paginas.panel.editar-sucursal',
            [
                'negocio' => $negocio,
                'usuario' => $usuario,
                'sucursal' => $sucursal,
            ],
        );
    }

    public function update(
        Negocio $negocio,
        Sucursal $sucursal,
    ): RedirectResponse {
        $sucursal->update([
            'nombre' => $_POST['nombre'] ?? $sucursal->nombre,
            'direccion' => $_POST['direccion'] ?? $sucursal->direccion,
            'telefono' => $_POST['telefono'] ?? $sucursal->telefono,
        ]);

        return to_route('panel.negocios.{negocio}.sucursales', [
            'negocio' => $negocio,
        ]);
    }
}
