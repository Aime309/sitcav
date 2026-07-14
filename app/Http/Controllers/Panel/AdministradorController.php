<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class AdministradorController extends Controller
{
    public function create(): View
    {
        return view('panel_registrarse');
    }

    public function store(): RedirectResponse
    {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $imagen = $_FILES['imagen'] ?? [];

        $usuario = new Usuario;
        $usuario->id = uniqid();
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->correo = $correo;
        $usuario->clave = password_hash($clave, PASSWORD_DEFAULT);
        $usuario->telefono = $telefono;

        $usuario->roles = json_encode([
            'administrador',
            'encargado',
            'vendedor',
        ]);

        if ($imagen['error'] === UPLOAD_ERR_OK) {
            $usuario->imagen = fopen($imagen['tmp_name'], 'rb');
        }

        $usuario->save();

        return to_route('panel.iniciar-sesion');
    }
}
