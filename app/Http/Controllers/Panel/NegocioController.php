<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class NegocioController extends Controller
{
    public function index(): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.negocios', ['usuario' => $usuario]);
    }

    public function store(Request $request): RedirectResponse
    {
        [
            'nombre' => $nombre,
            'rif' => $rif,
            'direccion' => $direccion,
            'telefono' => $telefono,
        ] = $request->validate([
            'nombre' => 'required',
            'rif' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);

        DB::transaction(static function () use (
            $nombre,
            $rif,
            $direccion,
            $telefono,
        ): void {
            $correo = $_SESSION['panel']['usuario']['correo'];
            $usuario = Usuario::query()->findOrFail($correo);

            $usuario->negocios()->create([
                'nombre' => $nombre,
                'rif' => $rif,
                'direccion' => $direccion,
                'telefono' => $telefono,
            ]);
        });

        return to_route('panel.negocios');
    }

    public function show(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.negocio', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function edit(Negocio $negocio): View
    {
        $correo = $_SESSION['panel']['usuario']['correo'];
        $usuario = Usuario::query()->findOrFail($correo);

        return view('paginas.panel.editar-negocio', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(
        Request $request,
        Negocio $negocio,
    ): RedirectResponse {
        [
            'nombre' => $nombre,
            'rif' => $rif,
            'direccion' => $direccion,
            'telefono' => $telefono,
        ] = $request->validate([
            'nombre' => 'required',
            'rif' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);

        $cargaInicialAbierta = $_POST['carga_inicial_abierta'] ?? '';

        $negocio->update([
            'nombre' => $nombre,
            'rif' => $rif,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'slug' => $negocio->newUniqueId(),
            'carga_inicial_abierta' => $cargaInicialAbierta === 'on'
                ? 1
                : 0,
        ]);

        return to_route('panel.negocios.{negocio}.editar', [
            'negocio' => $negocio,
        ]);
    }
}
