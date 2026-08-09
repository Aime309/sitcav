<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class SucursalController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('paginas.panel.sucursales', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        DB::transaction(static function () use ($negocio): void {
            $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

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

    public function show(Request $request, Negocio $negocio, Sucursal $sucursal): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('paginas.panel.sucursal', [
            'negocio' => $negocio,
            'usuario' => $usuario,
            'sucursal' => $sucursal,
        ]);
    }

    public function edit(Request $request, Negocio $negocio, Sucursal $sucursal): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view(
            'paginas.panel.editar-sucursal',
            [
                'negocio' => $negocio,
                'usuario' => $usuario,
                'sucursal' => $sucursal,
            ],
        );
    }

    public function update(Request $request, Negocio $negocio, Sucursal $sucursal): RedirectResponse
    {
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
