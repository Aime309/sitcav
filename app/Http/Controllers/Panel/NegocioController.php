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
    public function index(Request $request): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('panel_negocios', ['usuario' => $usuario]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(static function (): void {
            session_start();
            $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

            $negocio = $usuario->negocios()->create([
                'nombre' => $_POST['nombre'] ?? '',
                'rif' => $_POST['rif'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'slug' => $_POST['slug'] ?? '',
            ]);

            foreach ($_FILES['imagenes']['error'] as $indice => $error) {
                if ($error === UPLOAD_ERR_OK) {
                    $negocio->imagenes()->create([
                        'imagen' => fopen($_FILES['imagenes']['tmp_name'][$indice], 'rb'),
                    ]);
                }
            }
        });

        return to_route('panel.negocios');
    }

    public function show(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('panel_negocios_{negocio}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function edit(Request $request, Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        return view('panel_negocios_{negocio}_editar', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, Negocio $negocio): RedirectResponse
    {
        $negocio->update([
            'nombre' => $_POST['nombre'] ?? $negocio->nombre,
            'rif' => $_POST['rif'] ?? $negocio->rif,
            'direccion' => $_POST['direccion'] ?? $negocio->direccion,
            'telefono' => $_POST['telefono'] ?? $negocio->telefono,
            'slug' => $_POST['slug'] ?? $negocio->slug,
            'carga_inicial_abierta' => ($_POST['carga_inicial_abierta'] ?? '') === 'on'
                ? 1
                : 0,
        ]);

        return to_route('panel.negocios.{negocio}.editar', [
            'negocio' => $negocio,
        ]);
    }
}
