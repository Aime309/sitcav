<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class NegocioController extends Controller
{
    public function index(): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios', ['usuario' => $usuario]);
    }

    public function store(): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $rif = $_POST['rif'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $slug = $_POST['slug'] ?? '';

        PDO->beginTransaction();

        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

        $negocio = $usuario->negocios()->create([
            'id' => uniqid(),
            'nombre' => $nombre,
            'rif' => $rif,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'slug' => $slug,
        ]);

        foreach ($_FILES['imagenes']['error'] as $indice => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $negocio->imagenes()->create([
                    'id' => uniqid(),
                    'imagen' => fopen($_FILES['imagenes']['tmp_name'][$indice], 'rb'),
                ]);
            }
        }

        PDO->commit();

        return to_route('panel.negocios');
    }

    public function show(Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function edit(Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_editar', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function update(Negocio $negocio): RedirectResponse
    {
        $nombre = $_POST['nombre'];
        $rif = $_POST['rif'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $slug = $_POST['slug'];

        $cargaInicialAbierta = ($_POST['carga_inicial_abierta'] ?? '') === 'on'
            ? 1
            : 0;

        $negocio->nombre = $nombre;
        $negocio->rif = $rif;
        $negocio->direccion = $direccion;
        $negocio->telefono = $telefono;
        $negocio->slug = $slug;
        $negocio->carga_inicial_abierta = $cargaInicialAbierta;

        $negocio->save();

        return to_route('panel.negocios.{negocio}.editar', [
            'negocio' => $negocio,
        ]);
    }
}
