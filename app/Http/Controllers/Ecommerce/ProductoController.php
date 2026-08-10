<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Contracts\View\View;
use SplObjectStorage;

final class ProductoController extends Controller
{
    public function index(Negocio $negocio): View
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

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

        $usuarioId = $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] ?? null;
        $usuario = Cliente::query()->find($usuarioId);

        return view('paginas.ecommerce.productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function show(Negocio $negocio, Producto $producto): View
    {
        $usuarioId = $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] ?? null;
        $usuario = Cliente::query()->find($usuarioId);

        return view('paginas.ecommerce.producto', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    }
}
