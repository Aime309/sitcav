<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Proveedor;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function store(Negocio $negocio): RedirectResponse
    {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }

    public function update(
        Negocio $negocio,
        Proveedor $proveedor,
    ): RedirectResponse {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }
}
