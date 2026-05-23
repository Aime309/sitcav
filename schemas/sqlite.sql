BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    admin_id INTEGER REFERENCES usuarios(id),
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    direccion VARCHAR(300),
    foto_url VARCHAR(500),
    nombre VARCHAR(100) NOT NULL CHECK (LENGTH(nombre) > 0),
    apellidos VARCHAR(100),
    pregunta_1 VARCHAR(255),
    pregunta_2 VARCHAR(255),
    pregunta_3 VARCHAR(255),
    respuesta_1 VARCHAR(255),
    respuesta_2 VARCHAR(255),
    respuesta_3 VARCHAR(255),
    rol VARCHAR(50) NOT NULL CHECK (rol IN ('Empleado', 'Encargado'))
);

CREATE TABLE IF NOT EXISTS estados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id),
    nombre VARCHAR(100) NOT NULL UNIQUE CHECK (LENGTH(nombre) > 0)
);

CREATE TABLE IF NOT EXISTS localidades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_estado INTEGER NOT NULL REFERENCES estados(id),
    nombre VARCHAR(100) NOT NULL CHECK (LENGTH(nombre) > 0),
    UNIQUE(id_estado, nombre)
);

CREATE TABLE IF NOT EXISTS sectores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_localidad INTEGER NOT NULL REFERENCES localidades(id),
    nombre VARCHAR(100) NOT NULL CHECK (LENGTH(nombre) > 0),
    UNIQUE(id_localidad, nombre)
);

CREATE TABLE IF NOT EXISTS categorias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id),
    nombre VARCHAR(100) NOT NULL UNIQUE CHECK (LENGTH(nombre) > 0)
);

CREATE TABLE IF NOT EXISTS proveedores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_estado INTEGER REFERENCES estados(id),
    id_localidad INTEGER REFERENCES localidades(id),
    id_sector INTEGER REFERENCES sectores(id),
    direccion VARCHAR(300),
    nombre VARCHAR(200) NOT NULL CHECK (LENGTH(nombre) > 0),
    rif VARCHAR(50),
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_localidad INTEGER REFERENCES localidades(id),
    cedula VARCHAR(20) NOT NULL UNIQUE,
    direccion VARCHAR(300),
    nombre VARCHAR(100) NOT NULL CHECK (LENGTH(nombre) > 0),
    apellidos VARCHAR(100),
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS negocios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_localidad INTEGER NOT NULL REFERENCES localidades(id),
    id_sector INTEGER NOT NULL REFERENCES sectores(id),
    direccion VARCHAR(300),
    nombre VARCHAR(200) NOT NULL CHECK (LENGTH(nombre) > 0),
    rif VARCHAR(50),
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS tipos_pago (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id),
    nombre VARCHAR(100) NOT NULL CHECK (LENGTH(nombre) > 0)
);

CREATE TABLE IF NOT EXISTS productos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_categoria INTEGER NOT NULL REFERENCES categorias(id),
    id_proveedor INTEGER REFERENCES proveedores(id),
    cantidad_disponible INTEGER DEFAULT 0,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    dias_apartado INTEGER DEFAULT 0,
    dias_garantia INTEGER DEFAULT 0,
    imei VARCHAR(50),
    imagen_url VARCHAR(500),
    nombre VARCHAR(200) NOT NULL CHECK (LENGTH(nombre) > 0),
    precio_unitario_actual_dolares NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS cotizaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id),
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tasa_dolar_bolivares NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS ventas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cliente INTEGER NOT NULL REFERENCES clientes(id),
    id_vendedor INTEGER REFERENCES usuarios(id),
    cotizacion_dolar_bolivares NUMERIC(10, 2) DEFAULT 0,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS detalles_ventas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_venta INTEGER NOT NULL REFERENCES ventas(id),
    id_producto INTEGER NOT NULL REFERENCES productos(id),
    cantidad INTEGER NOT NULL,
    esta_apartado BOOLEAN NOT NULL DEFAULT FALSE,
    precio_unitario_tipo_dolares NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS pagos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_detalle_venta INTEGER NOT NULL REFERENCES detalles_ventas(id),
    id_tipo_pago INTEGER NOT NULL REFERENCES tipos_pago(id),
    cotizacion_dolar_bolivares NUMERIC(10, 2) NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    monto NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS apartados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cliente INTEGER NOT NULL REFERENCES clientes(id),
    estado VARCHAR(20) NOT NULL DEFAULT 'activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_limite DATETIME NOT NULL,
    monto_total NUMERIC(10, 2) NOT NULL,
    monto_pagado NUMERIC(10, 2) DEFAULT 0,
    observaciones TEXT
);

CREATE TABLE IF NOT EXISTS detalles_apartados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_apartado INTEGER NOT NULL REFERENCES apartados(id),
    id_producto INTEGER NOT NULL REFERENCES productos(id),
    cantidad INTEGER NOT NULL,
    precio_unitario NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS pagos_apartados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_apartado INTEGER NOT NULL REFERENCES apartados(id),
    fecha_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    monto NUMERIC(10, 2) NOT NULL,
    observacion VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS compras (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_proveedor INTEGER NOT NULL REFERENCES proveedores(id),
    cotizacion_dolar_bolivares NUMERIC(10, 2) NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS detalles_compras (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_compra INTEGER NOT NULL REFERENCES compras(id),
    id_producto INTEGER NOT NULL REFERENCES productos(id),
    cantidad INTEGER NOT NULL,
    precio_unitario_tipo_dolares NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS movimientos_inventario (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_producto INTEGER NOT NULL REFERENCES productos(id),
    cantidad INTEGER NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    motivo VARCHAR(50) NOT NULL,
    observacion VARCHAR(255),
    referencia_id INTEGER,
    referencia_tipo VARCHAR(20),
    tipo VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS reembolsos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id),
    id_venta INTEGER NOT NULL REFERENCES ventas(id),
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    monto_bolivares NUMERIC(10, 2) NOT NULL,
    monto_dolares NUMERIC(10, 2) NOT NULL,
    motivo VARCHAR(255),
    tasa_cambio NUMERIC(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS historial_precios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_producto INTEGER NOT NULL REFERENCES productos(id),
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    precio_anterior NUMERIC(10, 2) NOT NULL,
    precio_nuevo NUMERIC(10, 2) NOT NULL
);

INSERT INTO usuarios (admin_id, cedula, contrasena, direccion, foto_url, nombre, apellidos, pregunta_1, pregunta_2, pregunta_3, respuesta_1, respuesta_2, respuesta_3, rol) VALUES 
(NULL, '12345678', 'scrypt:32768:8:1$lHBAWMFWJ2IidHB7$bd878d2d0cd6379049d206be691cf221f8e40fa12ff7c09ab9119c612ea039e340e8c79610b5d826ab2be3ee1e7e8d72d592ad6af5a7bd81410d2c381a69bdee', NULL, NULL, 'Juan Pérez (Encargado)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Encargado'),
(NULL, '87654321', 'scrypt:32768:8:1$tYME3wHYSCjJs1fg$f060029bf3f4cbb29b253eb6ffde11666127bc8e391ff4d7fcc606078e9673467aeb5dfb8a6f4aabee1e77b94fdb20b2ac0472529e8446dcd3530b6f126766e1', NULL, NULL, 'María García (Empleado)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empleado'),
(NULL, '11223344', 'scrypt:32768:8:1$EooCC6xwJ0QEFaHa$be2650e9c8ca8c617490009ad519803c52464b3080427388ebea6dbb5ed665dad4107a6b37f9f135ecff216f8e629e7f36a676984dca843b06bd8e2297386ec6', NULL, NULL, 'Carlos López (Empleado)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empleado');

INSERT INTO estados (id_usuario, nombre) VALUES (1, 'Miranda');

INSERT INTO localidades (id_estado, nombre) VALUES (1, 'Caracas');

INSERT INTO sectores (id_localidad, nombre) VALUES (1, 'Centro');

INSERT INTO categorias (id_usuario, nombre) VALUES 
(1, 'Smartphones'),
(1, 'Laptops'),
(1, 'Accesorios'),
(1, 'Tablets');

INSERT INTO proveedores (id_estado, id_localidad, id_sector, direccion, nombre, rif, telefono) VALUES 
(1, 1, 1, NULL, 'TechSupply International', 'J-98765432-1', '0212-555-9876'),
(1, 1, NULL, NULL, 'ElectroDistribuidora CA', 'J-55544433-2', '0212-555-4433');

INSERT INTO clientes (id_localidad, cedula, direccion, nombre, apellidos, telefono) VALUES 
(1, '22334455', NULL, 'Ana', 'Rodríguez', '0424-111-2222'),
(1, '33445566', NULL, 'Pedro', 'Martínez', '0414-222-3333'),
(1, '44556677', NULL, 'Luisa', 'Fernández', '0426-333-4444');

INSERT INTO negocios (id_localidad, id_sector, direccion, nombre, rif, telefono) VALUES 
(1, 1, NULL, 'TechStore Venezuela', 'J-12345678-9', '0212-555-1234');

INSERT INTO tipos_pago (id_usuario, nombre) VALUES 
(1, 'Efectivo'),
(1, 'Transferencia'),
(1, 'Tarjeta de Débito'),
(1, 'Tarjeta de Crédito'),
(1, 'Pago Móvil');

INSERT INTO productos (id_categoria, id_proveedor, cantidad_disponible, codigo, descripcion, dias_apartado, dias_garantia, imei, imagen_url, nombre, precio_unitario_actual_dolares) VALUES 
(1, 1, 24, 'SAM-S24-001', 'Smartphone de última generación', 15, 365, NULL, 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400', 'Samsung Galaxy S24', 899.99),
(1, 1, 15, 'APL-IP15P-001', 'iPhone with chip A17 Pro', 20, 365, NULL, 'https://images.unsplash.com/photo-1710023038502-ba80a70a9f53?q=80&w=464&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'iPhone 15 Pro', 1199.99),
(2, 2, 10, 'DELL-INS15-001', 'Laptop para uso profesional', 30, 730, NULL, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400', 'Laptop Dell Inspiron 15', 649.99),
(2, 1, 8, 'APL-MBA-M2-001', 'Laptop ultraligera de Apple', 30, 365, NULL, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400', 'MacBook Air M2', 1299.99),
(3, 1, 48, 'APL-APP2-001', 'Audífonos con cancelación de ruido', 7, 365, NULL, 'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=400', 'AirPods Pro 2', 249.99),
(4, 1, 5, 'SAM-TABS9-001', 'Tablet Android premium', 15, 365, NULL, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400', 'Samsung Galaxy Tab S9', 799.99);

INSERT INTO cotizaciones (id_usuario, fecha_hora, tasa_dolar_bolivares) VALUES (1, '2026-05-14 13:24:54.494831', 35.5);

INSERT INTO ventas (id_cliente, id_vendedor, cotizacion_dolar_bolivares, fecha_creacion) VALUES (1, NULL, 35.5, '2026-05-14 13:24:54.498822');

INSERT INTO detalles_ventas (id_venta, id_producto, cantidad, esta_apartado, precio_unitario_tipo_dolares) VALUES 
(1, 1, 1, 0, 899.99),
(1, 5, 2, 0, 249.99);

-- TRIGGERS DE BASE DE DATOS --

-- 1. Auditoría de cambio de precio
CREATE TRIGGER IF NOT EXISTS audit_cambio_precio
AFTER UPDATE OF precio_unitario_actual_dolares ON productos
FOR EACH ROW
WHEN OLD.precio_unitario_actual_dolares <> NEW.precio_unitario_actual_dolares
BEGIN
    INSERT INTO historial_precios (id_producto, precio_anterior, precio_nuevo, fecha)
    VALUES (OLD.id, OLD.precio_unitario_actual_dolares, NEW.precio_unitario_actual_dolares, DATETIME('now'));
END;

-- 2. Prevención de stock negativo
CREATE TRIGGER IF NOT EXISTS prevent_negativo_stock
BEFORE UPDATE OF cantidad_disponible ON productos
FOR EACH ROW
WHEN NEW.cantidad_disponible < 0
BEGIN
    SELECT RAISE(ROLLBACK, 'Error: No hay suficiente stock para realizar esta operación.');
END;

-- 3. Sincronización automática de monto pagado en apartados
CREATE TRIGGER IF NOT EXISTS sync_pago_apartado
AFTER INSERT ON pagos_apartados
FOR EACH ROW
BEGIN
    UPDATE apartados 
    SET monto_pagado = monto_pagado + NEW.monto
    WHERE id = NEW.id_apartado;
END;

-- 4. Completado automático de apartado al alcanzar el monto total
CREATE TRIGGER IF NOT EXISTS check_apartado_completado
AFTER UPDATE OF monto_pagado ON apartados
FOR EACH ROW
WHEN NEW.monto_pagado >= NEW.monto_total AND OLD.estado = 'activo'
BEGIN
    UPDATE apartados SET estado = 'completado' WHERE id = NEW.id;
END;

COMMIT;
