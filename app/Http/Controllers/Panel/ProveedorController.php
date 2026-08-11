<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Proveedor;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ProveedorController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.proveedores', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse
    {
        ['nombre' => $nombre] = $request->validate([
            'nombre' => 'required',
        ]);

        $negocio->proveedores()->create([
            'nombre' => $nombre,
        ]);

        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }

    public function edit(Negocio $negocio, Proveedor $proveedor): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.editar-proveedor', [
            'negocio' => $negocio,
            'proveedor' => $proveedor,
            'usuario' => $usuario,
        ]);
    }

    public function update(
        Negocio $negocio,
        Proveedor $proveedor,
    ): RedirectResponse {
        ['nombre' => $nombre] = request()->validate([
            'nombre' => 'required',
        ]);

        $proveedor->update([
            'nombre' => $nombre,
            'slug' => $proveedor->newUniqueId(),
        ]);

        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }
}
