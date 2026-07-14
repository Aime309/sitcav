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
    public function index(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';

        $producto = new Producto;
        $producto->id = uniqid();
        $producto->negocio_id = $negocio->id;
        $producto->nombre = $nombre;
        $producto->descripcion = $descripcion;
        $producto->precio = $precio;
        $producto->save();

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    }

    public function edit(Request $request, Negocio $negocio, Producto $producto): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_productos_{producto}', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, Negocio $negocio, Producto $producto): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? $producto->nombre;
        $descripcion = $_POST['descripcion'] ?? $producto->descripcion;
        $precio = $_POST['precio'] ?? $producto->precio;

        $producto->nombre = $nombre;
        $producto->descripcion = $descripcion;
        $producto->precio = $precio;
        $producto->activo = empty($_POST['activo']) ? 0 : 1;
        $producto->save();

        return to_route(
            'panel.negocios.{negocio}.productos.{producto}',
            [
                'negocio' => $negocio,
                'producto' => $producto,
            ],
        );
    }
}
