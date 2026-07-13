<?php

declare(strict_types=1);

use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Reserva;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Pdo\Sqlite;

define('PDO', match (config('database.default')) {
    'sqlite' => new Sqlite('sqlite:' . config('database.connections.sqlite.database')),
});

PDO->query('CREATE TABLE IF NOT EXISTS usuarios (
    id TEXT PRIMARY KEY,
    nombre TEXT NOT NULL CHECK (length(nombre) > 0),
    apellido TEXT NOT NULL CHECK (length(apellido) > 0),
    correo TEXT NOT NULL UNIQUE CHECK (correo LIKE "%@gmail.com"),
    telefono TEXT NOT NULL UNIQUE CHECK (
        telefono LIKE "+58416_______"
        OR telefono LIKE "+58414_______"
        OR telefono LIKE "+58424_______"
        OR telefono LIKE "+58426_______"
    ),
    clave TEXT NOT NULL UNIQUE CHECK (length(clave) >= 8),
    roles TEXT NOT NULL CHECK (
        json_valid(roles)
        AND json_array_length(roles) > 0
        AND (
            json_extract(roles, "$[0]") IN ("administrador", "empleado", "vendedor")
            OR json_extract(roles, "$[1]") IN ("administrador", "empleado", "vendedor")
            OR json_extract(roles, "$[2]") IN ("administrador", "empleado", "vendedor")
            OR json_extract(roles, "$[3]") IN ("administrador", "empleado", "vendedor")
            OR json_extract(roles, "$[4]") IN ("administrador", "empleado", "vendedor")
        )
    ),
    imagen BLOB,
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en),

    UNIQUE (nombre, apellido)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS negocios (
    id TEXT PRIMARY KEY,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL CHECK (length(nombre) > 0),
    rif TEXT NOT NULL UNIQUE CHECK (length(rif) > 0),
    direccion TEXT NOT NULL CHECK (length(direccion) > 0),
    telefono TEXT NOT NULL UNIQUE CHECK (
        telefono LIKE "+58416_______"
        OR telefono LIKE "+58414_______"
        OR telefono LIKE "+58424_______"
        OR telefono LIKE "+58426_______"
    ),
    slug TEXT NOT NULL UNIQUE CHECK (length(slug) > 0),
    carga_inicial_abierta INT NOT NULL DEFAULT 1 CHECK (carga_inicial_abierta IN (0, 1)),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS negocios_imagenes (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    imagen BLOB NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS sucursales (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL CHECK (length(nombre) > 0),
    rif TEXT NOT NULL UNIQUE CHECK (length(rif) > 0),
    direccion TEXT NOT NULL CHECK (length(direccion) > 0),
    telefono TEXT NOT NULL UNIQUE CHECK (
        telefono LIKE "+58416_______"
        OR telefono LIKE "+58414_______"
        OR telefono LIKE "+58424_______"
        OR telefono LIKE "+58426_______"
    ),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS sucursales_imagenes (
    id TEXT PRIMARY KEY,
    sucursal_id TEXT NOT NULL REFERENCES sucursales(id) ON DELETE CASCADE,
    imagen BLOB NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS asignaciones (
    id TEXT PRIMARY KEY,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    negocio_id TEXT REFERENCES negocios(id),
    sucursal_id TEXT REFERENCES sucursales(id),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS clientes (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL CHECK (length(nombre) > 0),
    apellido TEXT NOT NULL CHECK (length(apellido) > 0),
    correo TEXT NOT NULL UNIQUE CHECK (correo LIKE "%@gmail.com"),
    clave TEXT NOT NULL UNIQUE CHECK (length(clave) >= 8),
    telefono TEXT NOT NULL UNIQUE CHECK (
        telefono LIKE "+58416_______"
        OR telefono LIKE "+58414_______"
        OR telefono LIKE "+58424_______"
        OR telefono LIKE "+58426_______"
    ),
    imagen BLOB,
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS proveedores (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL,
    rif TEXT NOT NULL UNIQUE,
    telefono TEXT NOT NULL UNIQUE,
    direccion TEXT NOT NULL,
    correo TEXT NOT NULL UNIQUE,
    imagen BLOB,
    activo INT NOT NULL DEFAULT 1,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS productos (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL CHECK (length(nombre) > 0),
    descripcion TEXT NOT NULL,
    precio REAL NOT NULL CHECK (precio >= 0),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS productos_imagenes (
    id TEXT PRIMARY KEY,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    imagen BLOB NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS inventarios (
    id TEXT PRIMARY KEY,
    establecimiento_tipo TEXT NOT NULL CHECK (establecimiento_tipo IN ("negocio", "sucursal")),
    establecimiento_id TEXT NOT NULL,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    stock INT NOT NULL CHECK (stock >= 0),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS compras (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    proveedor_id TEXT NOT NULL REFERENCES proveedores(id) ON DELETE CASCADE,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    observaciones TEXT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS compras_detalles (
    id TEXT PRIMARY KEY,
    compra_id TEXT NOT NULL REFERENCES compras(id) ON DELETE CASCADE,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    cantidad INT NOT NULL,
    precio REAL NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS ventas (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    cliente_id TEXT NOT NULL REFERENCES clientes(id) ON DELETE CASCADE,
    reserva_id TEXT REFERENCES reservas(id) ON DELETE SET NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS ventas_detalles (
    id TEXT PRIMARY KEY,
    venta_id TEXT NOT NULL REFERENCES ventas(id) ON DELETE CASCADE,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    cantidad INT NOT NULL,
    precio REAL NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS carritos (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    cliente_id TEXT NOT NULL REFERENCES clientes(id) ON DELETE CASCADE,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS carritos_detalles (
    id TEXT PRIMARY KEY,
    carrito_id TEXT NOT NULL REFERENCES carritos(id) ON DELETE CASCADE,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    cantidad INT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS reservas (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    cliente_id TEXT NOT NULL REFERENCES clientes(id) ON DELETE CASCADE,
    estado TEXT NOT NULL DEFAULT "activa",
    expira_en TEXT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS reservas_detalles (
    id TEXT PRIMARY KEY,
    reserva_id TEXT NOT NULL REFERENCES reservas(id) ON DELETE CASCADE,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    cantidad INT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

Route::redirect('/', 'panel/iniciar-sesion');

Route::prefix('panel')->group(static function (): void {
    Route::prefix('iniciar-sesion')->group(static function (): void {
        // Ver inicio de sesión del panel
        Route::get('/', static function (): View {
            return view('panel_iniciar-sesion');
        })->name('panel.iniciar-sesion');

        // Iniciar sesión en el panel
        Route::post('/', static function (): RedirectResponse {
            $correo = $_POST['correo'] ?? '';
            $clave = $_POST['clave'] ?? '';
            $usuario = Usuario::query()->where('correo', $correo)->first();

            if ($usuario && password_verify($clave, $usuario->clave)) {
                $usuario->roles = json_decode($usuario['roles'], true);

                $usuario['asignacion'] = PDO
                    ->query("
                        SELECT * FROM asignaciones
                        WHERE usuario_id = '{$usuario['id']}'
                    ")
                    ->fetch();

                session_start();
                $_SESSION['panel']['usuario']['id'] = $usuario->id;

                if (in_array('administrador', $usuario->roles)) {
                    return to_route('panel.negocios');
                }
            }

            return to_route('panel.iniciar-sesion');
        });
    });

    Route::prefix('registrarse')->group(static function (): void {
        // Ver registro de administrador del panel
        Route::get('/', static function (): View {
            return view('panel_registrarse');
        })->name('panel.registrarse');

        // Registrarse como administrador en el panel
        Route::post('/', static function (): RedirectResponse {
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
        });
    });

    // Cerrar sesión en el panel
    Route::get('cerrar-sesion', static function (): RedirectResponse {
        session_start();
        unset($_SESSION['panel']);

        return to_route('panel.iniciar-sesion');
    })->name('panel.cerrar-sesion');

    Route::prefix('negocios')->group(static function (): void {
        // Seleccionar establecimiento
        Route::get('/', static function (): View {
            session_start();
            $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
            $usuario->roles = json_decode($usuario['roles'], true);

            return view('panel_negocios', ['usuario' => $usuario]);
        })->name('panel.negocios');

        // Registrar negocio
        Route::post('/', static function (): RedirectResponse {
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
        });

        Route::prefix('{negocio}')->group(static function (): void {
            // Panel administrativo de un negocio
            Route::get(
                '/',
                static function (Negocio $negocio): View {
                    session_start();
                    $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                    $usuario->roles = json_decode($usuario['roles'], true);

                    return view('panel_negocios_{negocio}', [
                        'negocio' => $negocio,
                        'usuario' => $usuario,
                    ]);
                },
            )->name('panel.negocios.{negocio}');

            // Editar negocio
            Route::get(
                'editar',
                static function (Negocio $negocio): View {
                    session_start();
                    $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                    $usuario->roles = json_decode($usuario['roles'], true);

                    return view('panel_negocios_{negocio}_editar', [
                        'negocio' => $negocio,
                        'usuario' => $usuario,
                    ]);
                },
            )->name('panel.negocios.{negocio}.editar');

            // Actualizar negocio
            Route::post(
                '/',
                static function (Negocio $negocio): RedirectResponse {
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
                },
            );

            Route::prefix('perfil')->group(static function (): void {
                // Editar perfil
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_perfil', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.perfil');

                // Actualizar perfil
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);

                        $usuario['nombre'] = $_POST['nombre'];
                        $usuario['apellido'] = $_POST['apellido'];
                        $usuario['correo'] = $_POST['correo'];
                        $usuario['telefono'] = $_POST['telefono'];

                        $usuario->save();

                        return to_route('panel.negocios.{negocio}.perfil', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                // Actualizar clave
                Route::post(
                    'clave',
                    static function (Negocio $negocio): RedirectResponse {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $clave = $_POST['clave'] ?? '';
                        $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

                        $usuario->save();

                        return to_route('panel.negocios.{negocio}.perfil', [
                            'negocio' => $negocio,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.perfil.clave');
            });

            Route::prefix('empleados')->group(static function (): void {
                // Ver empleados
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);
                        $empleados = [];

                        foreach ($negocio->empleados as $empleado) {
                            $empleados[] = $empleado;
                        }

                        foreach ($negocio->sucursales as $sucursal) {
                            foreach ($sucursal->empleados as $empleado) {
                                $empleados[] = $empleado;
                            }
                        }

                        foreach ($empleados as $empleado) {
                            $empleado['asignaciones'] = PDO
                                ->query("
                                    SELECT * FROM asignaciones
                                    WHERE usuario_id = '{$empleado['id']}'
                                ")
                                ->fetchAll();

                            $empleado->roles = json_decode($empleado['roles'], true);
                        }

                        return view('panel_negocios_{negocio}_empleados', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                            'empleados' => $empleados,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.empleados');

                // Registrar empleado
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        $rol = $_POST['rol'] ?? '';
                        $nombre = $_POST['nombre'] ?? '';
                        $apellido = $_POST['apellido'] ?? '';
                        $correo = $_POST['correo'] ?? '';
                        $clave = $_POST['clave'] ?? '';
                        $telefono = $_POST['telefono'] ?? '';
                        $imagen = $_FILES['imagen'] ?? [];
                        $establecimiento = $_POST['establecimiento'] ?? '';
                        $negocio = Negocio::query()->find($establecimiento);
                        $sucursal = Sucursal::query()->find($establecimiento);

                        PDO->beginTransaction();

                        $empleado = new Usuario;
                        $empleado->id = uniqid();
                        $empleado->nombre = $nombre;
                        $empleado->apellido = $apellido;
                        $empleado->correo = $correo;
                        $empleado->clave = password_hash($clave, PASSWORD_DEFAULT);
                        $empleado->telefono = $telefono;

                        $empleado->roles = json_encode(match ($rol) {
                            'encargado' => ['encargado', 'vendedor'],
                            'vendedor' => ['vendedor'],
                        });

                        if ($imagen['error'] === UPLOAD_ERR_OK) {
                            $empleado->imagen = fopen($imagen['tmp_name'], 'rb');
                        }

                        $empleado->save();

                        PDO->prepare('INSERT INTO asignaciones
                            (id, usuario_id, negocio_id, sucursal_id) VALUES
                            (:id, :usuario_id, :negocio_id, :sucursal_id)'
                        )->execute([
                            ':id' => uniqid(),
                            ':usuario_id' => $empleado->id,
                            ':negocio_id' => $negocio?->id,
                            ':sucursal_id' => $sucursal?->id,
                        ]);

                        PDO->commit();

                        return to_route('panel.negocios.{negocio}.empleados', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                // Actualizar empleado
                Route::post(
                    '{empleado}',
                    static function (Negocio $negocio, Usuario $empleado): RedirectResponse {
                        PDO->beginTransaction();

                        $empleado->activo = ($_POST['activo'] ?? '') === 'on'
                            ? 1
                            : 0;

                        $empleado->roles = match ($_POST['rol'] ?? '') {
                            'encargado' => json_encode(['encargado', 'vendedor']),
                            'vendedor' => json_encode(['vendedor']),
                            default => $empleado->roles,
                        };

                        $empleado->save();

                        PDO->prepare('UPDATE asignaciones SET
                            negocio_id = :negocio_id,
                            sucursal_id = :sucursal_id,
                            actualizado_en = CURRENT_TIMESTAMP
                            WHERE usuario_id = :usuario_id'
                        )->execute([
                            ':negocio_id' => $_POST['establecimiento'] ?? null,
                            ':sucursal_id' => $_POST['establecimiento'] ?? null,
                            ':usuario_id' => $empleado->id,
                        ]);

                        PDO->commit();

                        return to_route('panel.negocios.{negocio}.empleados', [
                            'negocio' => $negocio,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.empleados.{empleado}');
            });

            Route::prefix('proveedores')->group(static function (): void {
                // Ver proveedores
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_proveedores', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.proveedores');

                // Registrar proveedor
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        return to_route('panel.negocios.{negocio}.proveedores', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                // Actualizar proveedor
                Route::post(
                    '{proveedor}',
                    static function (Negocio $negocio, Proveedor $proveedor): RedirectResponse {
                        return to_route('panel.negocios.{negocio}.proveedores', [
                            'negocio' => $negocio,
                        ]);
                    },
                );
            });

            Route::prefix('clientes')->group(static function (): void {
                // Ver clientes
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_clientes', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.clientes');

                // Registrar cliente
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        return to_route('panel_negocios_{negocio}_clientes', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                // Actualizar cliente
                Route::post(
                    '{cliente}',
                    static function (Negocio $negocio, Cliente $cliente): RedirectResponse {
                        return to_route('panel_negocios_{negocio}_clientes', [
                            'negocio' => $negocio,
                        ]);
                    },
                );
            });

            Route::prefix('productos')->group(static function (): void {
                // Ver productos
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_productos', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.productos');

                // Registrar producto
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        $nombre = $_POST['nombre'] ?? '';
                        $descripcion = $_POST['descripcion'] ?? '';
                        $precio = $_POST['precio'] ?? '';
                        $imagenes = [];
                        $stock = $_POST['stock'] ?? null;

                        $producto = new Producto;
                        $producto->id = uniqid();
                        $producto->negocio_id = $negocio->id;
                        $producto->nombre = $nombre;
                        $producto->descripcion = $descripcion;
                        $producto->precio = $precio;
                        $producto->imagenes = json_encode($imagenes);

                        PDO->beginTransaction();

                        $producto->save();

                        if ($stock !== null) {
                            PDO->prepare('INSERT INTO inventarios
                                (id, establecimiento_tipo, establecimiento_id, producto_id, stock) VALUES
                                (:id, :establecimiento_tipo, :establecimiento_id, :producto_id, :stock)
                            ')->execute([
                                ':id' => uniqid(),
                                ':establecimiento_tipo' => 'negocio',
                                ':establecimiento_id' => $negocio->id,
                                ':producto_id' => $producto->id,
                                ':stock' => $stock,
                            ]);
                        }

                        PDO->commit();

                        return to_route('panel.negocios.{negocio}.productos', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                Route::prefix('{producto}')->group(static function (): void {
                    // Editar producto
                    Route::get(
                        '/',
                        static function (
                            Negocio $negocio,
                            Producto $producto,
                        ): View {
                            $producto['stock'] = PDO
                                ->query("
                                    SELECT stock FROM inventarios
                                    WHERE (
                                        establecimiento_id = '$negocio->id'
                                        AND producto_id = '$producto->id'
                                    )
                                ")
                                ->fetchColumn();

                            session_start();
                            $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                            $usuario->roles = json_decode($usuario['roles'], true);

                            return view('panel_negocios_{negocio}_productos_{producto}', [
                                'negocio' => $negocio,
                                'producto' => $producto,
                                'usuario' => $usuario,
                            ]);
                        },
                    )->name('panel.negocios.{negocio}.productos.{producto}');

                    // Actualizar producto
                    Route::post(
                        '/',
                        static function (
                            Negocio $negocio,
                            Producto $producto,
                        ): RedirectResponse {
                            $nombre = $_POST['nombre'] ?? '';
                            $descripcion = $_POST['descripcion'] ?? '';
                            $precio = $_POST['precio'] ?? '';
                            $stock = $_POST['stock'] ?? null;

                            $producto->nombre = $nombre;
                            $producto->descripcion = $descripcion;
                            $producto->precio = $precio;

                            PDO->beginTransaction();

                            $producto->save();

                            if ($stock !== null) {
                                PDO->prepare('UPDATE inventarios SET
                                    stock = :stock,
                                    actualizado_en = CURRENT_TIMESTAMP
                                    WHERE (
                                        establecimiento_id = :establecimiento_id
                                        AND producto_id = :producto_id
                                    )
                                ')->execute([
                                    ':stock' => $stock,
                                    ':establecimiento_id' => $negocio->id,
                                    ':producto_id' => $producto->id,
                                ]);
                            }

                            PDO->commit();

                            return to_route(
                                'panel.negocios.{negocio}.productos.{producto}',
                                [
                                    'negocio' => $negocio,
                                    'producto' => $producto,
                                ],
                            );
                        },
                    );

                    // Activar producto
                    Route::get(
                        'activar',
                        static function (
                            Negocio $negocio,
                            Producto $producto,
                        ): RedirectResponse {
                            $producto->activo = 1;

                            $producto->save();

                            return to_route('panel.negocios.{negocio}.productos', [
                                'negocio' => $negocio,
                            ]);
                        },
                    )->name('panel.negocios.{negocio}.productos.{producto}.activar');

                    // Desactivar producto
                    Route::get(
                        'desactivar',
                        static function (
                            Negocio $negocio,
                            Producto $producto,
                        ): RedirectResponse {
                            $producto->activo = 0;

                            $producto->save();

                            return to_route(
                                'panel.negocios.{negocio}.productos',
                                [
                                    'negocio' => $negocio,
                                ],
                            );
                        },
                    )->name('panel.negocios.{negocio}.productos.{producto}.desactivar');
                });
            });

            Route::prefix('sucursales')->group(static function (): void {
                // Ver sucursales
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_sucursales', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.sucursales');

                Route::prefix('{sucursal}')->group(static function (): void {
                    // Panel administrativo de una sucursal
                    Route::get(
                        '/',
                        static function (
                            Negocio $negocio,
                            Sucursal $sucursal,
                        ): View {
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
                        },
                    )->name('panel.negocios.{negocio}.sucursales.{sucursal}');

                    // Editar sucursal
                    Route::get(
                        'editar',
                        static function (
                            Negocio $negocio,
                            Sucursal $sucursal,
                        ): View {
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
                        },
                    )->name('panel.negocios.{negocio}.sucursales.{sucursal}.editar');

                    // Actualizar sucursal
                    Route::post(
                        '/',
                        static function (
                            Negocio $negocio,
                            Sucursal $sucursal,
                        ): RedirectResponse {
                            return to_route(
                                'panel.negocios.{negocio}.sucursales.{sucursal}.editar',
                                [
                                    'negocio' => $negocio,
                                    'sucursal' => $sucursal,
                                ],
                            );
                        },
                    );
                });
            });

            Route::prefix('compras')->group(static function (): void {
                // Ver compras
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_compras', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.compras');

                // Registrar compra
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        return to_route('panel.negocios.{negocio}.compras', [
                            'negocio' => $negocio,
                        ]);
                    },
                );
            });

            Route::prefix('ventas')->group(static function (): void {
                // Ver ventas
                Route::get(
                    '/',
                    static function (Negocio $negocio): View {
                        session_start();
                        $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                        $usuario->roles = json_decode($usuario['roles'], true);

                        return view('panel_negocios_{negocio}_ventas', [
                            'negocio' => $negocio,
                            'usuario' => $usuario,
                        ]);
                    },
                )->name('panel.negocios.{negocio}.ventas');

                // Registrar venta
                Route::post(
                    '/',
                    static function (Negocio $negocio): RedirectResponse {
                        return to_route('panel.negocios.{negocio}.ventas', [
                            'negocio' => $negocio,
                        ]);
                    },
                );

                // Vender productos reservados
                Route::post(
                    '{reserva}',
                    static function (Negocio $negocio, Reserva $reserva): RedirectResponse {
                        return to_route('panel.negocios.{negocio}.ventas', [
                            'negocio' => $negocio,
                        ]);
                    },
                );
            });

            // Ver reservas
            Route::get(
                'reservas',
                static function (Negocio $negocio): View {
                    session_start();
                    $usuario = Usuario::query()->find($_SESSION['panel']['usuario']['id']);
                    $usuario->roles = json_decode($usuario['roles'], true);

                    return view('panel_negocios_{negocio}_reservas', [
                        'negocio' => $negocio,
                        'usuario' => $usuario,
                    ]);
                },
            )->name('panel.negocios.{negocio}.reservas');
        });
    });
});

Route::prefix('{negocio:slug}')->group(static function (): void {
    // Ecommerce de un negocio
    Route::get(
        '/',
        static function (Negocio $negocio): View {
            session_start();
            $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

            return view('{negocio}', [
                'negocio' => $negocio,
                'usuario' => $usuario,
            ]);
        },
    )->name('{negocio}');

    Route::prefix('productos')->group(static function (): void {
        // Ver productos de un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                return view('{negocio}_productos', [
                    'negocio' => $negocio,
                    'usuario' => $usuario,
                ]);
            },
        )->name('{negocio}.productos');

        // Ver producto de un negocio
        Route::get(
            '{producto}',
            static function (Negocio $negocio, Producto $producto): View {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                return view('{negocio}_productos_{producto}', [
                    'negocio' => $negocio,
                    'producto' => $producto,
                    'usuario' => $usuario,
                ]);
            },
        )->name('{negocio}.productos.{producto}');
    });

    Route::prefix('iniciar-sesion')->group(static function (): void {
        // Ver inicio de sesión en un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                return view('{negocio}_iniciar-sesion', ['negocio' => $negocio]);
            },
        )->name('{negocio}.iniciar-sesion');

        // Iniciar sesión en un negocio
        Route::post(
            '/',
            static function (Negocio $negocio): RedirectResponse {
                $correo = $_POST['correo'] ?? '';
                $clave = $_POST['clave'] ?? '';

                $usuario = Cliente::query()->where('correo', $correo)->first();

                if ($usuario && password_verify($clave, $usuario['clave'])) {
                    session_start();
                    $usuario['imagenes'] = json_decode($usuario['imagenes'], true);
                    $_SESSION['ecommerce'][$negocio->slug]['usuario']['id'] = $usuario->id;

                    return to_route('{negocio}', ['negocio' => $negocio]);
                }

                return to_route('{negocio}.iniciar-sesion', ['negocio' => $negocio]);
            },
        );
    });

    Route::prefix('registrarse')->group(static function (): void {
        // Ver registro de cliente en un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                return view('{negocio}_registrarse', ['negocio' => $negocio]);
            },
        )->name('{negocio}.registrarse');

        // Registrarse como cliente en un negocio
        Route::post(
            '/',
            static function (Negocio $negocio): RedirectResponse {
                $nombre = $_POST['nombre'] ?? '';
                $apellido = $_POST['apellido'] ?? '';
                $correo = $_POST['correo'] ?? '';
                $clave = $_POST['clave'] ?? '';
                $telefono = $_POST['telefono'] ?? '';
                $imagenes = [];

                $cliente = new Cliente;
                $cliente->id = uniqid();
                $cliente->nombre = $nombre;
                $cliente->apellido = $apellido;
                $cliente->correo = $correo;
                $cliente->clave = password_hash($clave, PASSWORD_DEFAULT);
                $cliente->telefono = $telefono;
                $cliente->imagenes = json_encode($imagenes);

                $cliente->save();

                return to_route('{negocio}.iniciar-sesion', [
                    'negocio' => $negocio['slug'],
                ]);
            },
        );
    });

    // Cerrar sesión en un negocio
    Route::get(
        'cerrar-sesion',
        static function (Negocio $negocio): RedirectResponse {
            session_start();
            unset($_SESSION['ecommerce'][$negocio->slug]);

            return to_route('{negocio}', ['negocio' => $negocio]);
        },
    )->name('{negocio}.cerrar-sesion');

    Route::prefix('perfil')->group(static function (): void {
        // Editar perfil en un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                return view('{negocio}_perfil', [
                    'negocio' => $negocio,
                    'usuario' => $usuario,
                ]);
            },
        )->name('{negocio}.perfil');

        // Actualizar perfil en un negocio
        Route::post(
            '/',
            static function (Negocio $negocio): RedirectResponse {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);
                $usuario['nombre'] = $_POST['nombre'];
                $usuario['apellido'] = $_POST['apellido'];
                $usuario['correo'] = $_POST['correo'];
                $usuario['telefono'] = $_POST['telefono'];

                $usuario->save();

                return to_route('{negocio}.perfil', ['negocio' => $negocio]);
            },
        );

        // Actualizar clave en un negocio
        Route::post(
            'clave',
            static function (Negocio $negocio): RedirectResponse {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);
                $clave = $_POST['clave'] ?? '';
                $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

                $usuario->save();

                return to_route('{negocio}.perfil', ['negocio' => $negocio]);
            },
        );
    });

    Route::prefix('carrito')->group(static function (): void {
        // Ver carrito en un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                return view('{negocio}_carrito', [
                    'negocio' => $negocio,
                    'usuario' => $usuario,
                ]);
            },
        )->name('{negocio}.carrito');

        Route::prefix('productos')->group(static function (): void {
            // Añadir producto al carrito en un negocio
            Route::post(
                'productos',
                static function (Negocio $negocio): RedirectResponse {
                    return to_route('{negocio}.carrito', ['negocio' => $negocio]);
                },
            );

            Route::prefix('{producto}')->group(static function (): void {
                // Actualizar cantidad de producto en el carrito en un negocio
                Route::post(
                    '/',
                    static function (
                        Negocio $negocio,
                        Producto $producto,
                    ): RedirectResponse {
                        return to_route('{negocio}.carrito', [
                            'negocio' => $negocio,
                            'producto' => $producto,
                        ]);
                    },
                );

                // Eliminar un producto del carrito en un negocio
                Route::post(
                    'eliminar',
                    static function (
                        Negocio $negocio,
                        Producto $producto,
                    ): RedirectResponse {
                        return to_route('{negocio}.carrito', ['negocio' => $negocio]);
                    },
                );
            });
        });
    });

    Route::prefix('reservas')->group(static function (): void {
        // Ver reservas en un negocio
        Route::get(
            '/',
            static function (Negocio $negocio): View {
                session_start();
                $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                return view('{negocio}_reservas', [
                    'negocio' => $negocio,
                    'usuario' => $usuario,
                ]);
            },
        )->name('{negocio}.reservas');

        // Reservar en un negocio
        Route::post(
            '/',
            static function (Negocio $negocio): RedirectResponse {
                return to_route('{negocio}.reservas.{reserva}', [
                    'negocio' => $negocio,
                    'reserva' => uniqid(),
                ]);
            },
        );

        Route::prefix('{reserva}')->group(static function (): void {
            // Ver reserva en un negocio
            Route::get(
                '/',
                static function (Negocio $negocio, Reserva $reserva): View {
                    session_start();
                    $usuario = Cliente::query()->find($_SESSION['ecommerce'][$negocio['slug']]['usuario']['id'] ?? null);

                    return view('{negocio}_reservas_{reserva}', [
                        'negocio' => $negocio,
                        'usuario' => $usuario,
                        'reserva' => $reserva,
                    ]);
                },
            )->name('{negocio}.reservas.{reserva}');

            // Cancelar reserva en un negocio
            Route::post(
                'cancelar',
                static function (Negocio $negocio, Reserva $reserva): RedirectResponse {
                    return to_route('{negocio}.reservas', ['negocio' => $negocio]);
                },
            );
        });
    });
});
