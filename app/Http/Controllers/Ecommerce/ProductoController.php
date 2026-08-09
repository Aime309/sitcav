<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use SplObjectStorage;

final class ProductoController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        foreach ($negocio->productos as $producto) {
            $producto['stocks'] = new SplObjectStorage;
            $producto['stock'] = 0;

            $stmt = PDO->prepare('SELECT * FROM inventarios WHERE producto_id = ?');
            $stmt->execute([$producto->id]);
            $inventarios = $stmt->fetchAll();

            foreach ($inventarios as $inventario) {
                $establecimiento = (
                    Negocio::query()->find($inventario['negocio_id'])
                    ?? Sucursal::query()->find($inventario['sucursal_id'])
                );

                $producto['stocks'][$establecimiento] = $inventario['stock'];
                $producto['stock'] += $inventario['stock'];
            }
        }

        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('paginas.ecommerce.productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function show(Request $request, Negocio $negocio, Producto $producto): View
    {
        $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

        return view('paginas.ecommerce.producto', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    }
}
