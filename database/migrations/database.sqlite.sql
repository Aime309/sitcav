CREATE TABLE IF NOT EXISTS "apartados" (
	"id"	INTEGER NOT NULL,
	"id_cliente"	INTEGER NOT NULL,
	"fecha_creacion"	DATETIME NOT NULL,
	"fecha_limite"	DATETIME NOT NULL,
	"monto_total"	NUMERIC(10, 2) NOT NULL,
	"monto_pagado"	NUMERIC(10, 2),
	"estado"	VARCHAR(20),
	"observaciones"	TEXT,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_cliente") REFERENCES "clientes"("id")
);
CREATE TABLE IF NOT EXISTS "categorias" (
	"id"	INTEGER NOT NULL,
	"id_usuario"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	PRIMARY KEY("id"),
	UNIQUE("nombre"),
	FOREIGN KEY("id_usuario") REFERENCES "usuarios"("id")
);
CREATE TABLE IF NOT EXISTS "clientes" (
	"id"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	"apellidos"	VARCHAR(100),
	"cedula"	VARCHAR(20) NOT NULL,
	"telefono"	VARCHAR(20),
	"direccion"	VARCHAR(300),
	"id_localidad"	INTEGER,
	UNIQUE("cedula"),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_localidad") REFERENCES "localidades"("id")
);
CREATE TABLE IF NOT EXISTS "compras" (
	"id"	INTEGER NOT NULL,
	"id_proveedor"	INTEGER NOT NULL,
	"fecha_creacion"	DATETIME NOT NULL,
	"cotizacion_dolar_bolivares"	NUMERIC(10, 2) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_proveedor") REFERENCES "proveedores"("id")
);
CREATE TABLE IF NOT EXISTS "cotizaciones" (
	"id"	INTEGER NOT NULL,
	"id_usuario"	INTEGER NOT NULL,
	"fecha_hora"	DATETIME NOT NULL,
	"tasa_dolar_bolivares"	NUMERIC(10, 2) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_usuario") REFERENCES "usuarios"("id")
);
CREATE TABLE IF NOT EXISTS "detalles_apartados" (
	"id"	INTEGER NOT NULL,
	"id_apartado"	INTEGER NOT NULL,
	"id_producto"	INTEGER NOT NULL,
	"cantidad"	INTEGER NOT NULL,
	"precio_unitario"	NUMERIC(10, 2) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_apartado") REFERENCES "apartados"("id"),
	FOREIGN KEY("id_producto") REFERENCES "productos"("id")
);
CREATE TABLE IF NOT EXISTS "detalles_compras" (
	"id"	INTEGER NOT NULL,
	"id_compra"	INTEGER NOT NULL,
	"id_producto"	INTEGER NOT NULL,
	"precio_unitario_tipo_dolares"	NUMERIC(10, 2) NOT NULL,
	"cantidad"	INTEGER NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_compra") REFERENCES "compras"("id"),
	FOREIGN KEY("id_producto") REFERENCES "productos"("id")
);
CREATE TABLE IF NOT EXISTS "detalles_ventas" (
	"id"	INTEGER NOT NULL,
	"id_venta"	INTEGER NOT NULL,
	"id_producto"	INTEGER NOT NULL,
	"precio_unitario_tipo_dolares"	NUMERIC(10, 2) NOT NULL,
	"cantidad"	INTEGER NOT NULL,
	"esta_apartado"	BOOLEAN,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_producto") REFERENCES "productos"("id"),
	FOREIGN KEY("id_venta") REFERENCES "ventas"("id")
);
CREATE TABLE IF NOT EXISTS "estados" (
	"id"	INTEGER NOT NULL,
	"id_usuario"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_usuario") REFERENCES "usuarios"("id")
);
CREATE TABLE IF NOT EXISTS "localidades" (
	"id"	INTEGER NOT NULL,
	"id_estado"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_estado") REFERENCES "estados"("id")
);
CREATE TABLE IF NOT EXISTS "movimientos_inventario" (
	"id"	INTEGER NOT NULL,
	"id_producto"	INTEGER NOT NULL,
	"tipo"	VARCHAR(20) NOT NULL,
	"cantidad"	INTEGER NOT NULL,
	"motivo"	VARCHAR(50) NOT NULL,
	"referencia_id"	INTEGER,
	"referencia_tipo"	VARCHAR(20),
	"fecha"	DATETIME NOT NULL,
	"observacion"	VARCHAR(255),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_producto") REFERENCES "productos"("id")
);
CREATE TABLE IF NOT EXISTS "negocios" (
	"id"	INTEGER NOT NULL,
	"id_localidad"	INTEGER NOT NULL,
	"id_sector"	INTEGER NOT NULL,
	"nombre"	VARCHAR(200) NOT NULL,
	"rif"	VARCHAR(50),
	"telefono"	VARCHAR(20),
	"direccion"	VARCHAR(300),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_localidad") REFERENCES "localidades"("id"),
	FOREIGN KEY("id_sector") REFERENCES "sectores"("id")
);
CREATE TABLE IF NOT EXISTS "pagos" (
	"id"	INTEGER NOT NULL,
	"id_tipo_pago"	INTEGER NOT NULL,
	"id_detalle_venta"	INTEGER NOT NULL,
	"fecha_creacion"	DATETIME NOT NULL,
	"cotizacion_dolar_bolivares"	NUMERIC(10, 2) NOT NULL,
	"monto"	NUMERIC(10, 2) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_detalle_venta") REFERENCES "detalles_ventas"("id"),
	FOREIGN KEY("id_tipo_pago") REFERENCES "tipos_pago"("id")
);
CREATE TABLE IF NOT EXISTS "pagos_apartados" (
	"id"	INTEGER NOT NULL,
	"id_apartado"	INTEGER NOT NULL,
	"monto"	NUMERIC(10, 2) NOT NULL,
	"fecha_pago"	DATETIME NOT NULL,
	"observacion"	VARCHAR(255),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_apartado") REFERENCES "apartados"("id")
);
CREATE TABLE IF NOT EXISTS "productos" (
	"id"	INTEGER NOT NULL,
	"nombre"	VARCHAR(200) NOT NULL,
	"descripcion"	TEXT,
	"codigo"	VARCHAR(100) NOT NULL,
	"imei"	VARCHAR(50),
	"id_categoria"	INTEGER NOT NULL,
	"id_proveedor"	INTEGER,
	"precio_unitario_actual_dolares"	NUMERIC(10, 2) NOT NULL,
	"cantidad_disponible"	INTEGER,
	"dias_garantia"	INTEGER,
	"dias_apartado"	INTEGER,
	"imagen_url"	VARCHAR(500),
	UNIQUE("codigo"),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_categoria") REFERENCES "categorias"("id"),
	FOREIGN KEY("id_proveedor") REFERENCES "proveedores"("id")
);
CREATE TABLE IF NOT EXISTS "proveedores" (
	"id"	INTEGER NOT NULL,
	"id_estado"	INTEGER,
	"id_localidad"	INTEGER,
	"id_sector"	INTEGER,
	"nombre"	VARCHAR(200) NOT NULL,
	"rif"	VARCHAR(50),
	"telefono"	VARCHAR(20),
	"direccion"	VARCHAR(300),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_estado") REFERENCES "estados"("id"),
	FOREIGN KEY("id_localidad") REFERENCES "localidades"("id"),
	FOREIGN KEY("id_sector") REFERENCES "sectores"("id")
);
CREATE TABLE IF NOT EXISTS "reembolsos" (
	"id"	INTEGER NOT NULL,
	"id_venta"	INTEGER NOT NULL,
	"id_usuario"	INTEGER NOT NULL,
	"monto_dolares"	NUMERIC(10, 2) NOT NULL,
	"monto_bolivares"	NUMERIC(10, 2) NOT NULL,
	"tasa_cambio"	NUMERIC(10, 2) NOT NULL,
	"motivo"	VARCHAR(255),
	"fecha"	DATETIME NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_usuario") REFERENCES "usuarios"("id"),
	FOREIGN KEY("id_venta") REFERENCES "ventas"("id")
);
CREATE TABLE IF NOT EXISTS "sectores" (
	"id"	INTEGER NOT NULL,
	"id_localidad"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_localidad") REFERENCES "localidades"("id")
);
CREATE TABLE IF NOT EXISTS "tipos_pago" (
	"id"	INTEGER NOT NULL,
	"id_usuario"	INTEGER NOT NULL,
	"nombre"	VARCHAR(100) NOT NULL,
	PRIMARY KEY("id"),
	FOREIGN KEY("id_usuario") REFERENCES "usuarios"("id")
);
CREATE TABLE IF NOT EXISTS "usuarios" (
	"id"	INTEGER NOT NULL,
	"cedula"	VARCHAR(20) NOT NULL,
	"contrasena"	VARCHAR(255) NOT NULL,
	"rol"	VARCHAR(50) NOT NULL,
	"activo"	BOOLEAN,
	"nombre"	VARCHAR(100) NOT NULL,
	"apellidos"	VARCHAR(100),
	"direccion"	VARCHAR(300),
	"foto_url"	VARCHAR(500),
	"pregunta_1"	VARCHAR(255),
	"respuesta_1"	VARCHAR(255),
	"pregunta_2"	VARCHAR(255),
	"respuesta_2"	VARCHAR(255),
	"pregunta_3"	VARCHAR(255),
	"respuesta_3"	VARCHAR(255),
	"admin_id"	INTEGER,
	UNIQUE("cedula"),
	PRIMARY KEY("id"),
	FOREIGN KEY("admin_id") REFERENCES "usuarios"("id")
);
CREATE TABLE IF NOT EXISTS "ventas" (
	"id"	INTEGER NOT NULL,
	"id_cliente"	INTEGER NOT NULL,
	"id_vendedor"	INTEGER,
	"fecha_creacion"	DATETIME NOT NULL,
	"cotizacion_dolar_bolivares"	NUMERIC(10, 2),
	PRIMARY KEY("id"),
	FOREIGN KEY("id_cliente") REFERENCES "clientes"("id"),
	FOREIGN KEY("id_vendedor") REFERENCES "usuarios"("id")
);
