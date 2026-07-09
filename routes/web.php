<?php

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

Route::get('/panel/iniciar-sesion', function () {
    return view('panel_iniciar-sesion');
});

Route::post('/panel/iniciar-sesion', function () {
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $stmt = PDO->prepare('SELECT * FROM usuarios WHERE correo = ?');
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($clave, $usuario['clave'])) {
        session_start();
        $usuario['roles'] = json_decode($usuario['roles'], true);
        $usuario['imagenes'] = json_decode($usuario['imagenes'], true);
        $_SESSION['panel']['usuario'] = $usuario;
    }
});

Route::get('/panel/registrarse', function () {
    return view('panel_registrarse');
});

Route::post('/panel/registrarse', function () {
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
});

Route::any('/panel/cerrar-sesion', function () {
    session_start();
    unset($_SESSION['panel']);
});

Route::get('/panel', function () {
    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    $negocios = PDO
        ->query("SELECT * FROM negocios WHERE usuario_id = '{$usuario['id']}'")
        ->fetchAll();

    $usuario['negocios'] = $negocios;

    foreach ($usuario['negocios'] as &$negocio) {
        $negocio['imagenes'] = json_decode($negocio['imagenes'], true);

        $sucursales = PDO
            ->query("SELECT * FROM sucursales WHERE negocio_id = '{$negocio['id']}'")
            ->fetchAll();

        foreach ($sucursales as &$sucursal) {
            $sucursal['imagenes'] = json_decode($sucursal['imagenes'], true);
        }

        $negocio['sucursales'] = $sucursales;
    }

    return view('panel', ['usuario' => $usuario]);
});

Route::post('/panel/negocios', function () {
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
});

Route::get('/panel/negocios/{negocio}', function ($negocio) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
    $stmt->execute([$negocio]);
    $negocio = $stmt->fetch();
    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    return view('panel_negocios_{negocio}', [
        'negocio' => $negocio,
        'usuario' => $usuario,
    ]);
});

Route::post('/panel/negocios/{negocio}', function ($negocio) {
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
});

Route::get('/panel/{negocio}/perfil', function ($negocio) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
    $stmt->execute([$negocio]);
    $negocio = $stmt->fetch();
    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    return view('panel_perfil', ['negocio' => $negocio, 'usuario' => $usuario]);
});

Route::post('/panel/{negocio}/perfil', function () {
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
});

Route::post('/panel/{negocio}/perfil/clave', function () {
    session_start();
    $usuario = &$_SESSION['panel']['usuario'];
    $clave = $_POST['clave'] ?? '';
    $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

    PDO->prepare('UPDATE usuarios SET
        clave = :clave,
        actualizado_en = CURRENT_TIMESTAMP
        WHERE id = :id
    ')->execute([':clave' => $usuario['clave'], ':id' => $usuario['id']]);
});

Route::get('/panel/{negocio}', function ($negocio) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
    $stmt->execute([$negocio]);
    $negocio = $stmt->fetch();

    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    return view('panel_{negocio}', ['negocio' => $negocio, 'usuario' => $usuario]);
});

Route::get('/panel/{negocio}/empleados', function () {
    return view('panel_{negocio}_empleados');
});

Route::post('/panel/{negocio}/empleados', function () {});
Route::post('/panel/{negocio}/empleados/{empleado}', function () {});

Route::get('/panel/{negocio}/proveedores', function () {
    return view('panel_{negocio}_proveedores');
});

Route::post('/panel/{negocio}/proveedores', function () {});
Route::post('/panel/{negocio}/proveedores/{proveedor}', function () {});

Route::get('/panel/{negocio}/clientes', function () {
    return view('panel_{negocio}_clientes');
});

Route::post('/panel/{negocio}/clientes', function () {});
Route::post('/panel/{negocio}/clientes/{cliente}', function () {});

Route::get('/panel/{negocio}/productos', function ($negocio) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
    $stmt->execute([$negocio]);
    $negocio = $stmt->fetch();

    $negocio['productos'] = PDO
        ->query("SELECT * FROM productos WHERE negocio_id = '{$negocio['id']}'")
        ->fetchAll();

    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    return view('panel_{negocio}_productos', [
        'negocio' => $negocio,
        'usuario' => $usuario,
    ]);
});

Route::post('/panel/{negocio}/productos', function ($negocio) {
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
});

Route::get('/panel/{negocio}/productos/{producto}', function ($negocio, $producto) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE id = ?');
    $stmt->execute([$negocio]);
    $negocio = $stmt->fetch();

    $stmt = PDO->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$producto]);
    $producto = $stmt->fetch();

    $producto['stock'] = PDO
        ->query("SELECT stock FROM inventarios WHERE establecimiento_id = '{$negocio['id']}' AND producto_id = '{$producto['id']}'")
        ->fetchColumn();

    session_start();
    $usuario = $_SESSION['panel']['usuario'];

    return view('panel_{negocio}_productos_{producto}', [
        'negocio' => $negocio,
        'producto' => $producto,
        'usuario' => $usuario,
    ]);
});

Route::post('/panel/{negocio}/productos/{producto}', function ($negocio, $producto) {
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
});

Route::any('/panel/{negocio}/productos/{producto}/activar', function ($negocio, $producto) {
    PDO->prepare('UPDATE productos SET
        activo = 1,
        actualizado_en = CURRENT_TIMESTAMP
        WHERE id = :id
    ')->execute([':id' => $producto]);
});

Route::any('/panel/{negocio}/productos/{producto}/desactivar', function ($negocio, $producto) {
    PDO->prepare('UPDATE productos SET
        activo = 0,
        actualizado_en = CURRENT_TIMESTAMP
        WHERE id = :id
    ')->execute([':id' => $producto]);
});

Route::get('/panel/{negocio}/sucursales', function () {
    return view('panel_{negocio}_sucursales');
});

Route::get('/panel/{negocio}/sucursales/{sucursal}', function () {
    return view('panel_{negocio}');
});

Route::post('/panel/{negocio}/sucursales/{sucursal}', function () {});

Route::get('/panel/{negocio}/compras', function () {
    return view('panel_{negocio}_compras');
});

Route::post('/panel/{negocio}/compras', function () {});

Route::get('/panel/{negocio}/ventas', function () {
    return view('panel_{negocio}_ventas');
});

Route::get('/panel/{negocio}/reservas', function () {
    return view('panel_{negocio}_reservas');
});

Route::post('/panel/{negocio}/ventas', function () {});
Route::post('/panel/{negocio}/ventas/desde-reserva', function () {});

Route::get('/{slug}', function ($slug) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
    $stmt->execute([$slug]);
    $negocio = $stmt->fetch();

    session_start();
    $usuario = $_SESSION['ecommerce'][$slug]['usuario'] ?? [];

    return view('{slug}', ['negocio' => $negocio, 'usuario' => $usuario]);
});

Route::get('/{slug}/productos', function () {
    return view('{slug}_productos');
});

Route::get('/{slug}/productos/{producto}', function () {
    return view('{slug}_productos_{producto}');
});

Route::get('/{slug}/iniciar-sesion', function ($slug) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
    $stmt->execute([$slug]);
    $negocio = $stmt->fetch();

    return view('{slug}_iniciar-sesion', ['negocio' => $negocio]);
});

Route::post('/{slug}/iniciar-sesion', function ($slug) {
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $stmt = PDO->prepare('SELECT * FROM clientes WHERE correo = ?');
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($clave, $usuario['clave'])) {
        session_start();
        $usuario['imagenes'] = json_decode($usuario['imagenes'], true);
        $_SESSION['ecommerce'][$slug]['usuario'] = $usuario;
    }
});

Route::get('/{slug}/registrarse', function ($slug) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
    $stmt->execute([$slug]);
    $negocio = $stmt->fetch();

    return view('{slug}_registrarse', ['negocio' => $negocio]);
});

Route::post('/{slug}/registrarse', function ($slug) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
    $stmt->execute([$slug]);
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
    $_SESSION['ecommerce'][$slug]['usuario'] = $cliente;
});

Route::any('/{slug}/cerrar-sesion', function ($slug) {
    session_start();
    unset($_SESSION['ecommerce'][$slug]);
});

Route::get('/{slug}/perfil', function ($slug) {
    $stmt = PDO->prepare('SELECT * FROM negocios WHERE slug = ?');
    $stmt->execute([$slug]);
    $negocio = $stmt->fetch();
    session_start();
    $usuario = $_SESSION['ecommerce'][$slug]['usuario'];

    return view('{slug}_perfil', [
        'negocio' => $negocio,
        'usuario' => $usuario,
    ]);
});

Route::post('/{slug}/perfil', function ($slug) {
    session_start();
    $usuario = &$_SESSION['ecommerce'][$slug]['usuario'];
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
});

Route::post('/{slug}/perfil/clave', function ($slug) {
    session_start();
    $usuario = &$_SESSION['ecommerce'][$slug]['usuario'];
    $clave = $_POST['clave'] ?? '';
    $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

    PDO->prepare('UPDATE clientes SET
        clave = :clave,
        actualizado_en = CURRENT_TIMESTAMP
        WHERE id = :id
    ')->execute([':clave' => $usuario['clave'], ':id' => $usuario['id']]);
});

Route::get('/{slug}/carrito', function () {
    return view('{slug}_carrito');
});

Route::post('/{slug}/carrito/items', function () {});
Route::post('/{slug}/carrito/items/{elemento}', function () {});
Route::post('/{slug}/carrito/items/{elemento}/eliminar', function () {});

Route::get('/{slug}/reservas', function () {
    return view('{slug}_reservas');
});

Route::post('/{slug}/reservas', function () {});

Route::get('/{slug}/reservas/{reserva}', function () {
    return view('{slug}_reservas_{reserva}');
});

Route::post('/{slug}/reservas/{reserva}/cancelar', function () {});
