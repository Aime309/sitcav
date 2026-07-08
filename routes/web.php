<?php

use Illuminate\Support\Facades\Route;

define('PDO', match ($_ENV['DB_CONNECTION']) {
    'sqlite' => new PDO\Sqlite('sqlite:' . __DIR__ . '/../database/database.sqlite'),
});

PDO->query('CREATE TABLE IF NOT EXISTS usuarios (
    id TEXT PRIMARY KEY,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    correo TEXT NOT NULL UNIQUE,
    telefono TEXT NOT NULL UNIQUE,
    clave TEXT NOT NULL UNIQUE,
    roles TEXT NOT NULL,
    imagenes BLOB NOT NULL,
    activo INT NOT NULL DEFAULT 1,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (nombre, apellido)
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS negocios (
    id TEXT PRIMARY KEY,
    nombre TEXT NOT NULL,
    rif TEXT NOT NULL UNIQUE,
    direccion TEXT NOT NULL,
    telefono TEXT NOT NULL UNIQUE,
    slug TEXT NOT NULL UNIQUE,
    imagenes BLOB NOT NULL,
    carga_inicial_cerrada INT NOT NULL DEFAULT 0,
    activo INT NOT NULL DEFAULT 1,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS sucursales (
    id TEXT PRIMARY KEY,
    negocio_id TEXT NOT NULL REFERENCES negocios(id) ON DELETE CASCADE,
    nombre TEXT NOT NULL,
    rif TEXT NOT NULL UNIQUE,
    direccion TEXT NOT NULL,
    telefono TEXT NOT NULL UNIQUE,
    imagenes BLOB NOT NULL,
    activo INT NOT NULL DEFAULT 1,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS asignaciones (
    id TEXT PRIMARY KEY,
    usuario_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
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
    nombre TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    precio REAL NOT NULL,
    imagenes BLOB NOT NULL,
    activo INT NOT NULL DEFAULT 1,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
) STRICT')->execute();

PDO->query('CREATE TABLE IF NOT EXISTS inventarios (
    id TEXT PRIMARY KEY,
    establecimiento_tipo TEXT NOT NULL,
    establecimiento_id TEXT NOT NULL,
    producto_id TEXT NOT NULL REFERENCES productos(id) ON DELETE CASCADE,
    stock INT NOT NULL,
    creado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
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
    cliente_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
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
    cliente_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
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
    cliente_id TEXT NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
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

Route::post('/panel/iniciar-sesion', function () {});

Route::get('/panel/registrarse', function () {
    return view('panel_registrarse');
});

Route::post('/panel/registrarse', function () {});

Route::post('/panel/cerrar-sesion', function () {});

Route::get('/panel', function () {
    return view('panel');
});

Route::get('/panel/perfil', function () {
    return view('panel_perfil');
});

Route::post('/panel/perfil', function () {});
Route::post('/panel/perfil/clave', function () {});

Route::get('/panel/{negocio}', function () {
    return view('panel_{negocio}');
});

Route::post('/panel/{negocio}', function () {});

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

Route::get('/panel/{negocio}/productos', function () {
    return view('panel_{negocio}_productos');
});

Route::post('/panel/{negocio}/productos', function () {});
Route::post('/panel/{negocio}/productos/{producto}', function () {});

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

Route::post('/panel/{negocio}/ventas', function () {});
Route::post('/panel/{negocio}/ventas/desde-reserva', function () {});

Route::get('/{slug}', function () {
    return view('{slug}');
});

Route::get('/{slug}/productos', function () {
    return view('{slug}_productos');
});

Route::get('/{slug}/productos/{producto}', function () {
    return view('{slug}_productos_{producto}');
});

Route::get('/{slug}/iniciar-sesion', function () {
    return view('{slug}_iniciar-sesion');
});

Route::post('/{slug}/iniciar-sesion', function () {});

Route::get('/{slug}/registrarse', function () {
    return view('{slug}_registrarse');
});

Route::post('/{slug}/registrarse', function () {});

Route::get('/{slug}/perfil', function () {
    return view('{slug}_perfil');
});

Route::post('/{slug}/perfil', function () {});
Route::post('/{slug}/perfil/clave', function () {});

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
