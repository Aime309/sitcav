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
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('paginas.panel.productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';

        PDO->beginTransaction();

        $producto = $negocio->productos()->create([
            'id' => uniqid(),
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
        ]);

        foreach ($_FILES['imagenes']['error'] as $indice => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $producto->imagenes()->create([
                    'id' => uniqid(),
                    'imagen' => fopen($_FILES['imagenes']['tmp_name'][$indice], 'rb'),
                ]);
            }
        }

        PDO->commit();

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    }

    public function edit(Request $request, Negocio $negocio, Producto $producto): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('paginas.panel.editar-producto', [
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
