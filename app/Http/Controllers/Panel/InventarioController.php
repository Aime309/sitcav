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

final class InventarioController extends Controller
{
    public function index(Request $request, Negocio $negocio): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        foreach ($negocio->productos as $producto) {
            $producto['stock'] = PDO
                ->query("
                    SELECT stock
                    FROM inventarios
                    WHERE negocio_id = '{$negocio['id']}'
                    AND producto_id = '{$producto['id']}'
                ")->fetchColumn() ?: 0;
        }

        return view('panel_negocios_{negocio}_inventario', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, Negocio $negocio, Producto $producto): RedirectResponse
    {
        $stock = $_POST['stock'] ?? 0;

        $inventario = PDO
            ->query("
                SELECT * FROM inventarios
                WHERE negocio_id = '{$negocio['id']}'
                AND producto_id = '{$producto['id']}'
            ")
            ->fetch();

        if ($inventario) {
            PDO->prepare(
                'UPDATE inventarios SET
                stock = :stock,
                actualizado_en = CURRENT_TIMESTAMP
                WHERE negocio_id = :negocio_id
                AND producto_id = :producto_id'
            )->execute([
                ':stock' => $stock,
                ':negocio_id' => $negocio->id,
                ':producto_id' => $producto->id,
            ]);
        } else {
            PDO->prepare(
                'INSERT INTO inventarios
                (id, negocio_id, producto_id, stock) VALUES
                (:id, :negocio_id, :producto_id, :stock)'
            )->execute([
                ':id' => uniqid(),
                ':negocio_id' => $negocio->id,
                ':producto_id' => $producto->id,
                ':stock' => $stock,
            ]);
        }

        return to_route('panel.negocios.{negocio}.inventario', [
            'negocio' => $negocio,
        ]);
    }
}
