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
    public function index(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        foreach ($negocio->productos as $producto) {
            $producto['stock'] = PDO
                ->query("
                    SELECT stock
                    FROM inventarios
                    WHERE negocio_slug = '$negocio->slug'
                    AND producto_id = '$producto->id'
                ")->fetchColumn() ?: 0;
        }

        return view('paginas.panel.inventario', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(
        Request $request,
        Negocio $negocio,
        Producto $producto,
    ): RedirectResponse {
        ['stock' => $stock] = $request->validate([
            'stock' => 'required',
        ]);

        $inventario = PDO
            ->query("
                SELECT * FROM inventarios
                WHERE negocio_slug = '{$negocio->slug}'
                AND producto_id = '{$producto->id}'
            ")
            ->fetch();

        if ($inventario) {
            PDO->prepare(
                'UPDATE inventarios SET
                stock = :stock
                WHERE negocio_slug = :negocio_slug
                AND producto_id = :producto_id'
            )->execute([
                ':stock' => $stock,
                ':negocio_slug' => $negocio->slug,
                ':producto_id' => $producto->id,
            ]);
        } else {
            PDO->prepare(
                'INSERT INTO inventarios
                (id, negocio_slug, producto_id, stock) VALUES
                (:id, :negocio_slug, :producto_id, :stock)'
            )->execute([
                ':negocio_slug' => $negocio->slug,
                ':producto_id' => $producto->id,
                ':stock' => $stock,
            ]);
        }

        return to_route('panel.negocios.{negocio}.inventario', [
            'negocio' => $negocio,
        ]);
    }
}
