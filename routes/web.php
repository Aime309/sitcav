<?php

declare(strict_types=1);

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

define('PDO', match ($_ENV['DB_CONNECTION']) {
    'sqlite' => new PDO\Sqlite('sqlite:' . __DIR__ . '/../database/database.sqlite'),
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
    imagenes TEXT NOT NULL CHECK (
        json_valid(imagenes)
        AND json_array_length(imagenes) >= 0
    ),
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
    imagenes TEXT NOT NULL CHECK (
        json_valid(imagenes)
        AND json_array_length(imagenes) >= 0
    ),
    carga_inicial_cerrada INT NOT NULL DEFAULT 0 CHECK (carga_inicial_cerrada IN (0, 1)),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
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
    imagenes BLOB NOT NULL CHECK (
        json_valid(imagenes)
        AND json_array_length(imagenes) >= 0
    ),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP CHECK (actualizado_en >= creado_en)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS asignaciones (
    id TEXT PRIMARY KEY,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
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
    imagenes TEXT NOT NULL CHECK (
        json_valid(imagenes)
        AND json_array_length(imagenes) >= 0
    ),
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
    imagenes BLOB NOT NULL,
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
    imagenes TEXT NOT NULL CHECK (
        json_valid(imagenes)
        AND json_array_length(imagenes) >= 0
    ),
    activo INT NOT NULL DEFAULT 1 CHECK (activo IN (0, 1)),
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

// Ver inicio de sesión del panel
Route::get('/panel/iniciar-sesion', static function (): View {
    return view('panel_iniciar-sesion');
})->name('panel.iniciar-sesion');

// Iniciar sesión en el panel
Route::post('/panel/iniciar-sesion', static function (): RedirectResponse {
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $stmt = PDO->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($clave, $usuario['clave'])) {
        session_start();
        $usuario['roles'] = json_decode($usuario['roles'], true);
        $usuario['imagenes'] = json_decode($usuario['imagenes'], true);

        $usuario['asignacion'] = PDO
            ->query("
                SELECT * FROM asignaciones
                WHERE usuario_id = '{$usuario['id']}'
            ")
            ->fetch();

        $_SESSION['panel']['usuario'] = $usuario;

        if (in_array('Administrador', $usuario['roles'])) {
            return to_route('panel.negocios');
        }

        if ($usuario['asignacion']['establecimiento_tipo'] === 'negocio') {
            return to_route('panel.negocios.{negocio}', [
                'negocio' => $usuario['asignacion']['establecimiento_id'],
            ]);
        }

        $sucursal = PDO
            ->query("
                SELECT * FROM sucursales
                WHERE id = '{$usuario['asignacion']['establecimiento_id']}'
            ")
            ->fetch();

        return to_route('panel.negocios.{negocio}.sucursales.{sucursal}', [
            'negocio' => $sucursal['negocio_id'],
            'sucursal' => $sucursal['id'],
        ]);
    }

    return to_route('panel.iniciar-sesion');
});

// Ver registro de administrador del panel
Route::get('/panel/registrarse', static function (): View {
    return view('panel_registrarse');
})->name('panel.registrarse');

// Registrarse como administrador en el panel
Route::post('/panel/registrarse', static function (): RedirectResponse {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $imagenes = [];

    foreach ($_FILES['imagenes']['error'] as $indice => $error) {
        if ($error === UPLOAD_ERR_OK) {
            $imagenes[] = [
                'name' => $_FILES['imagenes']['name'][$indice],
                'full_path' => $_FILES['imagenes']['full_path'][$indice],
                'type' => $_FILES['imagenes']['type'][$indice],
                'tmp_name' => $_FILES['imagenes']['tmp_name'][$indice],
                'error' => $_FILES['imagenes']['error'][$indice],
                'size' => $_FILES['imagenes']['size'][$indice],
            ];
        }
    }

    foreach ($imagenes as &$imagen) {
        if (!is_dir(__DIR__ . '/../public/storage')) {
            mkdir(__DIR__ . '/../public/storage');
        }

        move_uploaded_file(
            $imagen['tmp_name'],
            __DIR__ . "/../public/storage/{$imagen['name']}",
        );

        $imagen = "./storage/{$imagen['name']}";
    }

    $usuario = [
        'id' => uniqid(),
        'nombre' => $nombre,
        'apellido' => $apellido,
        'correo' => $correo,
        'clave' => password_hash($clave, PASSWORD_DEFAULT),
        'telefono' => $telefono,
        'roles' => ['Administrador', 'Encargado', 'Vendedor'],
        'imagenes' => $imagenes,
    ];

    PDO->prepare('INSERT INTO usuarios
        (id, nombre, apellido, correo, telefono, clave, roles, imagenes) VALUES
        (:id, :nombre, :apellido, :correo, :telefono, :clave, :roles, :imagenes)
    ')->execute([
        ':id' => $usuario['id'],
        ':nombre' => $usuario['nombre'],
        ':apellido' => $usuario['apellido'],
        ':correo' => $usuario['correo'],
        ':clave' => $usuario['clave'],
        ':telefono' => $usuario['telefono'],
        ':roles' => json_encode($usuario['roles']),
        ':imagenes' => json_encode($usuario['imagenes']),
    ]);

    session_start();
    $_SESSION['panel']['usuario'] = $usuario;

    return to_route('panel.negocios');
});

// Cerrar sesión en el panel
Route::get('/panel/cerrar-sesion', static function (): RedirectResponse {
    session_start();
    unset($_SESSION['panel']);

    return to_route('panel.iniciar-sesion');
})->name('panel.cerrar-sesion');

// Seleccionar establecimiento
Route::get('/panel/negocios', static function (): View {
    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    $negocios = PDO
        ->query("SELECT * FROM negocios WHERE usuario_id = '{$usuario['id']}'")
        ->fetchAll();

    $usuario['negocios'] = $negocios;

    foreach ($usuario['negocios'] as &$negocio) {
        $negocio['imagenes'] = json_decode($negocio['imagenes'], true);

        $sucursales = PDO
            ->query("
                SELECT * FROM sucursales
                WHERE negocio_id = '{$negocio['id']}'
            ")
            ->fetchAll();

        foreach ($sucursales as &$sucursal) {
            $sucursal['imagenes'] = json_decode($sucursal['imagenes'], true);
        }

        $negocio['sucursales'] = $sucursales;
    }

    return view('panel_negocios', ['usuario' => $usuario]);
})->name('panel.negocios');

// Registrar negocio
Route::post('/panel/negocios', static function (): RedirectResponse {
    $nombre = $_POST['nombre'] ?? '';
    $rif = $_POST['rif'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $imagenes = [];

    foreach ($_FILES['imagenes']['error'] as $indice => $error) {
        if ($error === UPLOAD_ERR_OK) {
            $imagenes[] = [
                'name' => $_FILES['imagenes']['name'][$indice],
                'full_path' => $_FILES['imagenes']['full_path'][$indice],
                'type' => $_FILES['imagenes']['type'][$indice],
                'tmp_name' => $_FILES['imagenes']['tmp_name'][$indice],
                'error' => $_FILES['imagenes']['error'][$indice],
                'size' => $_FILES['imagenes']['size'][$indice],
            ];
        }
    }

    foreach ($imagenes as &$imagen) {
        if (!is_dir(__DIR__ . '/../public/storage')) {
            mkdir(__DIR__ . '/../public/storage');
        }

        move_uploaded_file(
            $imagen['tmp_name'],
            __DIR__ . "/../public/storage/{$imagen['name']}",
        );

        $imagen = "./storage/{$imagen['name']}";
    }

    session_start();

    $negocio = [
        'id' => uniqid(),
        'usuario_id' => $_SESSION['panel']['usuario']['id'],
        'nombre' => $nombre,
        'rif' => $rif,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'slug' => $slug,
        'imagenes' => $imagenes,
    ];

    PDO->prepare('INSERT INTO negocios
        (id, usuario_id, nombre, rif, direccion, telefono, slug, imagenes) VALUES
        (:id, :usuario_id, :nombre, :rif, :direccion, :telefono, :slug, :imagenes)
    ')->execute([
        ':id' => $negocio['id'],
        ':usuario_id' => $negocio['usuario_id'],
        ':nombre' => $negocio['nombre'],
        ':rif' => $negocio['rif'],
        ':direccion' => $negocio['direccion'],
        ':telefono' => $negocio['telefono'],
        ':slug' => $negocio['slug'],
        ':imagenes' => json_encode($negocio['imagenes']),
    ]);

    return to_route('panel.negocios');
});

// Editar negocio
$route = Route::get(
    '/panel/negocios/{negocio}/editar',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();
        session_start();
        $usuario = $_SESSION['panel']['usuario'];

        return view('panel_negocios_{negocio}_editar', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.editar');

// Actualizar negocio
Route::post(
    '/panel/negocios/{negocio}',
    static function (string $negocio): RedirectResponse {
        $nombre = $_POST['nombre'];
        $rif = $_POST['rif'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $slug = $_POST['slug'];
        $cargaInicialCerrada = ($_POST['carga_inicial_cerrada'] ?? '') === 'on' ? 1 : 0;

        PDO->prepare('UPDATE negocios SET
            nombre = :nombre,
            rif = :rif,
            direccion = :direccion,
            telefono = :telefono,
            slug = :slug,
            carga_inicial_cerrada = :carga_inicial_cerrada,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([
            ':nombre' => $nombre,
            ':rif' => $rif,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':slug' => $slug,
            ':carga_inicial_cerrada' => $cargaInicialCerrada,
            ':id' => $negocio,
        ]);

        return to_route('panel.negocios.{negocio}.editar', [
            'negocio' => $negocio,
        ]);
    },
);

// Editar perfil
Route::get(
    '/panel/negocios/{negocio}/perfil',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        session_start();
        $usuario = $_SESSION['panel']['usuario'];

        return view('panel_negocios_{negocio}_perfil', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.perfil');

// Actualizar perfil
Route::post(
    '/panel/negocios/{negocio}/perfil',
    static function (string $negocio): RedirectResponse {
        session_start();
        $usuario = &$_SESSION['panel']['usuario'];
        $usuario['nombre'] = $_POST['nombre'];
        $usuario['apellido'] = $_POST['apellido'];
        $usuario['correo'] = $_POST['correo'];
        $usuario['telefono'] = $_POST['telefono'];

        PDO->prepare('UPDATE usuarios SET
            nombre = :nombre,
            apellido = :apellido,
            correo = :correo,
            telefono = :telefono,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':correo' => $usuario['correo'],
            ':telefono' => $usuario['telefono'],
            ':id' => $usuario['id'],
        ]);

        return to_route('panel.negocios.{negocio}.perfil', [
            'negocio' => $negocio,
        ]);
    },
);

// Actualizar clave
Route::post(
    '/panel/negocios/{negocio}/perfil/clave',
    static function (string $negocio): RedirectResponse {
        session_start();
        $usuario = &$_SESSION['panel']['usuario'];
        $clave = $_POST['clave'] ?? '';
        $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

        PDO->prepare('UPDATE usuarios SET
            clave = :clave,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([':clave' => $usuario['clave'], ':id' => $usuario['id']]);

        return to_route('panel.negocios.{negocio}.perfil', [
            'negocio' => $negocio,
        ]);
    },
)->name('panel.negocios.{negocio}.perfil.clave');

// Panel administrativo de un negocio
Route::get(
    '/panel/negocios/{negocio}',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        session_start();
        $usuario = $_SESSION['panel']['usuario'];

        return view('panel_negocios_{negocio}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('panel.negocios.{negocio}');

// Ver empleados
Route::get(
    '/panel/negocios/{negocio}/empleados',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_empleados');
    },
)
    ->name('panel.negocios.{negocio}.empleados');

// Registrar empleado
Route::post(
    '/panel/negocios/{negocio}/empleados',
    static function (string $negocio): RedirectResponse {
        return to_route('panel.negocios.{negocio}.empleados', [
            'negocio' => $negocio,
        ]);
    },
);

// Actualizar empleado
Route::post(
    '/panel/negocios/{negocio}/empleados/{empleado}',
    static function (string $negocio, string $empleado): RedirectResponse {
        return to_route('panel_negocios_{negocio}_empleados', [
            'negocio' => $negocio,
        ]);
    },
);

// Ver proveedores
Route::get(
    '/panel/negocios/{negocio}/proveedores',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_proveedores');
    },
)
    ->name('panel.negocios.{negocio}.proveedores');

// Registrar proveedor
Route::post(
    '/panel/negocios/{negocio}/proveedores',
    static function (string $negocio): RedirectResponse {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    },
);

// Actualizar proveedor
Route::post(
    '/panel/negocios/{negocio}/proveedores/{proveedor}',
    static function (string $negocio, string $proveedor): RedirectResponse {
        return to_route('panel.negocios.{negocio}.proveedores', [
            'negocio' => $negocio,
        ]);
    },
);

// Ver clientes
Route::get(
    '/panel/negocios/{negocio}/clientes',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_clientes');
    },
)
    ->name('panel.negocios.{negocio}.clientes');

// Registrar cliente
Route::post(
    '/panel/negocios/{negocio}/clientes',
    static function (string $negocio): RedirectResponse {
        return to_route('panel_negocios_{negocio}_clientes', [
            'negocio' => $negocio,
        ]);
    },
);

// Actualizar cliente
Route::post(
    '/panel/negocios/{negocio}/clientes/{cliente}',
    static function (string $negocio, string $cliente): RedirectResponse {
        return to_route('panel_negocios_{negocio}_clientes', [
            'negocio' => $negocio,
        ]);
    },
);

// Ver productos
Route::get(
    '/panel/negocios/{negocio}/productos',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        $negocio['productos'] = PDO
            ->query("
                SELECT * FROM productos
                WHERE negocio_id = '{$negocio['id']}'
            ")
            ->fetchAll();

        session_start();
        $usuario = $_SESSION['panel']['usuario'];

        return view('panel_negocios_{negocio}_productos', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.productos');

// Registrar producto
Route::post(
    '/panel/negocios/{negocio}/productos',
    static function (string $negocio): RedirectResponse {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $imagenes = [];
        $stock = $_POST['stock'] ?? null;

        $producto = [
            'id' => uniqid(),
            'negocio_id' => $negocio,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'imagenes' => $imagenes,
        ];

        PDO->beginTransaction();

        PDO->prepare('INSERT INTO productos
            (id, negocio_id, nombre, descripcion, precio, imagenes) VALUES
            (:id, :negocio_id, :nombre, :descripcion, :precio, :imagenes)
        ')->execute([
            ':id' => $producto['id'],
            ':negocio_id' => $producto['negocio_id'],
            ':nombre' => $producto['nombre'],
            ':descripcion' => $producto['descripcion'],
            ':precio' => $producto['precio'],
            ':imagenes' => json_encode($producto['imagenes']),
        ]);

        if ($stock !== null) {
            PDO->prepare('INSERT INTO inventarios
                (id, establecimiento_tipo, establecimiento_id, producto_id, stock) VALUES
                (:id, :establecimiento_tipo, :establecimiento_id, :producto_id, :stock)
            ')->execute([
                ':id' => uniqid(),
                ':establecimiento_tipo' => 'negocio',
                ':establecimiento_id' => $negocio,
                ':producto_id' => $producto['id'],
                ':stock' => $stock,
            ]);
        }

        PDO->commit();

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    },
);

// Editar producto
Route::get(
    '/panel/negocios/{negocio}/productos/{producto}',
    static function (string $negocio, string $producto): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        $stmt = PDO->prepare('SELECT * FROM productos WHERE id = ?');
        $stmt->execute([$producto]);
        $producto = $stmt->fetch();

        $producto['stock'] = PDO
            ->query("
                SELECT stock FROM inventarios
                WHERE (
                    establecimiento_id = '{$negocio['id']}'
                    AND producto_id = '{$producto['id']}
                )'
            ")
            ->fetchColumn();

        session_start();
        $usuario = $_SESSION['panel']['usuario'];

        return view('panel_negocios_{negocio}_productos_{producto}', [
            'negocio' => $negocio,
            'producto' => $producto,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.productos.{producto}');

// Actualizar producto
Route::post(
    '/panel/negocios/{negocio}/productos/{producto}',
    static function (string $negocio, string $producto): RedirectResponse {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $stock = $_POST['stock'] ?? null;

        PDO->beginTransaction();

        PDO->prepare('UPDATE productos SET
            nombre = :nombre,
            descripcion = :descripcion,
            precio = :precio,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':id' => $producto,
        ]);

        if ($stock !== null) {
            PDO->prepare('UPDATE inventarios SET
                stock = :stock,
                actualizado_en = CURRENT_TIMESTAMP
                WHERE establecimiento_id = :establecimiento_id AND producto_id = :producto_id
            ')->execute([
                ':stock' => $stock,
                ':establecimiento_id' => $negocio,
                ':producto_id' => $producto,
            ]);
        }

        PDO->commit();

        return to_route('panel.negocios.{negocio}.productos.{producto}', [
            'negocio' => $negocio,
            'producto' => $producto,
        ]);
    },
);

// Activar producto
Route::get(
    '/panel/negocios/{negocio}/productos/{producto}/activar',
    static function (string $negocio, string $producto): RedirectResponse {
        PDO->prepare('UPDATE productos SET
            activo = 1,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([':id' => $producto]);

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.productos.{producto}.activar');

// Desactivar producto
Route::get(
    '/panel/negocios/{negocio}/productos/{producto}/desactivar',
    static function (string $negocio, string $producto): RedirectResponse {
        PDO->prepare('UPDATE productos SET
            activo = 0,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([':id' => $producto]);

        return to_route('panel.negocios.{negocio}.productos', [
            'negocio' => $negocio,
        ]);
    },
)
    ->name('panel.negocios.{negocio}.productos.{producto}.desactivar');

// Ver sucursales
Route::get(
    '/panel/negocios/{negocio}/sucursales',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_sucursales');
    },
)
    ->name('panel.negocios.{negocio}.sucursales');

// Panel administrativo de una sucursal
Route::get(
    '/panel/negocios/{negocio}/sucursales/{sucursal}',
    static function (string $negocio, string $sucursal): View {
        return view('panel_negocios_{negocio}_sucursales_{sucursal}');
    },
)
    ->name('panel.negocios.{negocio}.sucursales.{sucursal}');

// Editar sucursal
Route::get(
    '/panel/negocios/{negocio}/sucursales/{sucursal}/editar',
    static function (string $negocio, string $sucursal): View {
        return view('panel_negocios_{negocio}_sucursales_{sucursal}_editar');
    },
)->name('panel.negocios.{negocio}.sucursales.{sucursal}.editar');

// Actualizar sucursal
Route::post(
    '/panel/negocios/{negocio}/sucursales/{sucursal}',
    static function (string $negocio, string $sucursal): RedirectResponse {
        return to_route('panel.negocios.{negocio}.sucursales.{sucursal}.editar', [
            'negocio' => $negocio,
            'sucursal' => $sucursal,
        ]);
    },
);

// Ver compras
Route::get(
    '/panel/negocios/{negocio}/compras',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_compras');
    },
)
    ->name('panel.negocios.{negocio}.compras');

// Registrar compra
Route::post(
    '/panel/negocios/{negocio}/compras',
    static function (string $negocio): RedirectResponse {
        return to_route('panel.negocios.{negocio}.compras', [
            'negocio' => $negocio,
        ]);
    },
);

// Ver ventas
Route::get(
    '/panel/negocios/{negocio}/ventas',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_ventas');
    },
)
    ->name('panel.negocios.{negocio}.ventas');

// Registrar venta
Route::post(
    '/panel/negocios/{negocio}/ventas',
    static function (string $negocio): RedirectResponse {
        return to_route('panel.negocios.{negocio}.ventas', [
            'negocio' => $negocio,
        ]);
    },
);

// Vender productos reservados
Route::post(
    '/panel/negocios/{negocio}/ventas/{reserva}',
    static function (string $negocio, string $reserva): RedirectResponse {
        return to_route('panel.negocios.{negocio}.ventas', [
            'negocio' => $negocio,
        ]);
    },
);

// Ver reservas
Route::get(
    '/panel/negocios/{negocio}/reservas',
    static function (string $negocio): View {
        return view('panel_negocios_{negocio}_reservas');
    },
)
    ->name('panel.negocios.{negocio}.reservas');

// Ecommerce de un negocio
Route::get(
    '/{negocio}',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        session_start();
        $usuario = $_SESSION['ecommerce'][$negocio['slug']]['usuario'] ?? [];

        return view('{negocio}', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('{negocio}');

// Ver productos de un negocio
Route::get(
    '/{negocio}/productos',
    static function (string $negocio): View {
        return view('{negocio}_productos');
    },
)
    ->name('{negocio}.productos');

// Ver producto de un negocio
Route::get(
    '/{negocio}/productos/{producto}',
    static function (string $negocio, string $producto): View {
        return view('{negocio}_productos_{producto}');
    },
)
    ->name('{negocio}.productos.{producto}');

// Ver inicio de sesión en un negocio
Route::get(
    '/{negocio}/iniciar-sesion',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        return view('{negocio}_iniciar-sesion', ['negocio' => $negocio]);
    },
)
    ->name('{negocio}.iniciar-sesion');

// Iniciar sesión en un negocio
Route::post(
    '/{negocio}/iniciar-sesion',
    static function (string $negocio): RedirectResponse {
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';

        $stmt = PDO->prepare('SELECT * FROM clientes WHERE correo = ?');
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($clave, $usuario['clave'])) {
            session_start();
            $usuario['imagenes'] = json_decode($usuario['imagenes'], true);
            $_SESSION['ecommerce'][$negocio]['usuario'] = $usuario;

            return to_route('{negocio}', ['negocio' => $negocio]);
        }

        return to_route('{negocio}.iniciar-sesion', ['negocio' => $negocio]);
    },
);

// Ver registro de cliente en un negocio
Route::get(
    '/{negocio}/registrarse',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();

        return view('{negocio}_registrarse', ['negocio' => $negocio]);
    },
)
    ->name('{negocio}.registrarse');

// Registrarse como cliente en un negocio
Route::post(
    '/{negocio}/registrarse',
    static function (string $negocio): RedirectResponse {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $imagenes = [];

        $cliente = [
            'id' => uniqid(),
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'clave' => password_hash($clave, PASSWORD_DEFAULT),
            'telefono' => $telefono,
            'imagenes' => $imagenes,
        ];

        PDO->prepare('INSERT INTO clientes
            (id, negocio_id, nombre, apellido, correo, telefono, clave, imagenes) VALUES
            (:id, :negocio_id, :nombre, :apellido, :correo, :telefono, :clave, :imagenes)
        ')->execute([
            ':id' => $cliente['id'],
            ':negocio_id' => $negocio['id'],
            ':nombre' => $cliente['nombre'],
            ':apellido' => $cliente['apellido'],
            ':correo' => $cliente['correo'],
            ':clave' => $cliente['clave'],
            ':telefono' => $cliente['telefono'],
            ':imagenes' => json_encode($cliente['imagenes']),
        ]);

        session_start();
        $_SESSION['ecommerce'][$negocio['slug']]['usuario'] = $cliente;

        return to_route('{negocio}', ['negocio' => $negocio['slug']]);
    },
);

// Cerrar sesión en un negocio
Route::get(
    '/{negocio}/cerrar-sesion',
    static function (string $negocio): RedirectResponse {
        session_start();
        unset($_SESSION['ecommerce'][$negocio]);

        return to_route('{negocio}', ['negocio' => $negocio]);
    },
)
    ->name('{negocio}.cerrar-sesion');

// Editar perfil en un negocio
Route::get(
    '/{negocio}/perfil',
    static function (string $negocio): View {
        $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
        $stmt->execute([$negocio]);
        $negocio = $stmt->fetch();
        session_start();
        $usuario = $_SESSION['ecommerce'][$negocio['slug']]['usuario'];

        return view('{negocio}_perfil', [
            'negocio' => $negocio,
            'usuario' => $usuario,
        ]);
    },
)
    ->name('{negocio}.perfil');

// Actualizar perfil en un negocio
Route::post(
    '/{negocio}/perfil',
    static function (string $negocio): RedirectResponse {
        session_start();
        $usuario = &$_SESSION['ecommerce'][$negocio]['usuario'];
        $usuario['nombre'] = $_POST['nombre'];
        $usuario['apellido'] = $_POST['apellido'];
        $usuario['correo'] = $_POST['correo'];
        $usuario['telefono'] = $_POST['telefono'];

        PDO->prepare('UPDATE clientes SET
            nombre = :nombre,
            apellido = :apellido,
            correo = :correo,
            telefono = :telefono,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([
            ':nombre' => $usuario['nombre'],
            ':apellido' => $usuario['apellido'],
            ':correo' => $usuario['correo'],
            ':telefono' => $usuario['telefono'],
            ':id' => $usuario['id'],
        ]);

        return to_route('{negocio}.perfil', ['negocio' => $negocio]);
    },
);

// Actualizar clave en un negocio
Route::post(
    '/{negocio}/perfil/clave',
    static function (string $negocio): RedirectResponse {
        session_start();
        $usuario = &$_SESSION['ecommerce'][$negocio]['usuario'];
        $clave = $_POST['clave'] ?? '';
        $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

        PDO->prepare('UPDATE clientes SET
            clave = :clave,
            actualizado_en = CURRENT_TIMESTAMP
            WHERE id = :id
        ')->execute([':clave' => $usuario['clave'], ':id' => $usuario['id']]);

        return to_route('{negocio}.perfil', ['negocio' => $negocio]);
    },
);

// Ver carrito en un negocio
Route::get(
    '/{negocio}/carrito',
    static function (string $negocio): View {
        return view('{negocio}_carrito');
    },
)
    ->name('{negocio}.carrito');

// Añadir producto al carrito en un negocio
Route::post(
    '/{negocio}/carrito/productos',
    static function (string $negocio): RedirectResponse {
        return to_route('{negocio}.carrito', ['negocio' => $negocio]);
    },
);

// Actualizar cantidad de producto en el carrito en un negocio
Route::post(
    '/{negocio}/carrito/productos/{producto}',
    static function (string $negocio): RedirectResponse {
        return to_route('{negocio}.carrito', ['negocio' => $negocio]);
    },
);

// Eliminar un producto del carrito en un negocio
Route::post(
    '/{negocio}/carrito/productos/{producto}/eliminar',
    static function (string $negocio): RedirectResponse {
        return to_route('{negocio}.carrito', ['negocio' => $negocio]);
    },
);

// Ver reservas en un negocio
Route::get(
    '/{negocio}/reservas',
    static function (string $negocio): View {
        return view('{negocio}_reservas');
    },
)
    ->name('{negocio}.reservas');

// Reservar en un negocio
Route::post(
    '/{negocio}/reservas',
    static function (string $negocio): RedirectResponse {
        return to_route('{negocio}.reservas.{reserva}', [
            'negocio' => $negocio,
            'reserva' => uniqid(),
        ]);
    },
);

// Ver reserva en un negocio
Route::get(
    '/{negocio}/reservas/{reserva}',
    static function (string $negocio, string $reserva): View {
        return view('{negocio}_reservas_{reserva}');
    },
)
    ->name('{negocio}.reservas.{reserva}');

// Cancelar reserva en un negocio
Route::post(
    '/{negocio}/reservas/{reserva}/cancelar',
    static function (string $negocio, string $reserva): RedirectResponse {
        return to_route('{negocio}.reservas', ['negocio' => $negocio]);
    },
);
