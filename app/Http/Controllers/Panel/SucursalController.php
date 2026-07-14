<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class SucursalController extends Controller
{
    public function index(Negocio $negocio): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view('panel_negocios_{negocio}_sucursales', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    }

    public function store(Negocio $negocio): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $rif = $_POST['rif'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';

        PDO->beginTransaction();

        $sucursal = $negocio->sucursales()->create([
            'id' => uniqid(),
            'nombre' => $nombre,
            'rif' => $rif,
            'direccion' => $direccion,
            'telefono' => $telefono,
        ]);

        foreach ($_FILES['imagenes']['error'] as $indice => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $sucursal->imagenes()->create([
                    'id' => uniqid(),
                    'imagen' => fopen($_FILES['imagenes']['tmp_name'][$indice], 'rb'),
                ]);
            }
        }

        PDO->commit();

        return to_route('panel.negocios.{negocio}.sucursales', [
            'negocio' => $negocio,
        ]);
    }

    public function show(Negocio $negocio, Sucursal $sucursal): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view(
            'panel_negocios_{negocio}_sucursales_{sucursal}',
            [
                'negocio' => $negocio,
                'usuario' => $usuario,
                'sucursal' => $sucursal,
            ],
        );
    }

    public function edit(Negocio $negocio, Sucursal $sucursal): View
    {
        session_start();
        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
        $usuario->roles = json_decode($usuario['roles'], true);

        return view(
            'panel_negocios_{negocio}_sucursales_{sucursal}_editar',
            [
                'negocio' => $negocio,
                'usuario' => $usuario,
                'sucursal' => $sucursal,
            ],
        );
    }

    public function update(Negocio $negocio, Sucursal $sucursal): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $rif = $_POST['rif'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';

        $sucursal->nombre = $nombre;
        $sucursal->rif = $rif;
        $sucursal->direccion = $direccion;
        $sucursal->telefono = $telefono;

        $sucursal->save();

        return to_route(
            'panel.negocios.{negocio}.sucursales.{sucursal}.editar',
            [
                'negocio' => $negocio,
                'sucursal' => $sucursal,
            ],
        );
    }
}
