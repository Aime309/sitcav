<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ProductoController extends Controller
{
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse {
        [
            'nombre' => $nombre,
            'precio' => $precio,
        ] = $request->validate([
            'nombre' => 'required',
            'precio' => 'required',
        ]);

        $negocio->productos()->create([
            'nombre' => $nombre,
            'precio' => $precio,
        ]);

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    }

    public function edit(Negocio $negocio, Producto $producto): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.editar-producto', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    }

    public function update(
        Request $request,
        Negocio $negocio,
        Producto $producto,
    ): RedirectResponse {
        [
            'nombre' => $nombre,
            'precio' => $precio,
        ] = $request->validate([
            'nombre' => 'required',
            'precio' => 'required',
        ]);

        $producto->update([
            'nombre' => $nombre,
            'precio' => $precio,
        ]);

        return to_route(
            'panel.negocios.{negocio}.productos.{producto}',
            [
                'negocio' => $negocio,
                'producto' => $producto,
            ],
        );
    }
}
