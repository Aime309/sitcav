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
    public function index(Request $request, Negocio $negocio): View
    {
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('paginas.panel.proveedores', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Request $request, Negocio $negocio): RedirectResponse
    {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }

    public function update(Request $request, Negocio $negocio, Proveedor $proveedor): RedirectResponse
    {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    }
}
