BEGIN TRANSACTION;
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
INSERT INTO "categorias" VALUES (1,1,'Smartphones'),
 (2,1,'Laptops'),
 (3,1,'Accesorios'),
 (4,1,'Tablets');
INSERT INTO "clientes" VALUES (1,'Ana','Rodríguez','22334455','0424-111-2222',NULL,1),
 (2,'Pedro','Martínez','33445566','0414-222-3333',NULL,1),
 (3,'Luisa','Fernández','44556677','0426-333-4444',NULL,1);
INSERT INTO "cotizaciones" VALUES (1,1,'2026-05-14 13:24:54.494831',35.5);
INSERT INTO "detalles_ventas" VALUES (1,1,1,899.99,1,0),
 (2,1,5,249.99,2,0);
INSERT INTO "estados" VALUES (1,1,'Miranda');
INSERT INTO "localidades" VALUES (1,1,'Caracas');
INSERT INTO "negocios" VALUES (1,1,1,'TechStore Venezuela','J-12345678-9','0212-555-1234',NULL);
INSERT INTO "productos" VALUES (1,'Samsung Galaxy S24','Smartphone de última generación','SAM-S24-001',NULL,1,1,899.99,24,365,15,'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400'),
 (2,'iPhone 15 Pro','iPhone con chip A17 Pro','APL-IP15P-001',NULL,1,1,1199.99,15,365,20,'https://images.unsplash.com/photo-1710023038502-ba80a70a9f53?q=80&w=464&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
 (3,'Laptop Dell Inspiron 15','Laptop para uso profesional','DELL-INS15-001',NULL,2,2,649.99,10,730,30,'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400'),
 (4,'MacBook Air M2','Laptop ultraligera de Apple','APL-MBA-M2-001',NULL,2,1,1299.99,8,365,30,'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400'),
 (5,'AirPods Pro 2','Audífonos con cancelación de ruido','APL-APP2-001',NULL,3,1,249.99,48,365,7,'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=400'),
 (6,'Samsung Galaxy Tab S9','Tablet Android premium','SAM-TABS9-001',NULL,4,1,799.99,5,365,15,'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400');
INSERT INTO "proveedores" VALUES (1,1,1,1,'TechSupply International','J-98765432-1','0212-555-9876',NULL),
 (2,1,1,NULL,'ElectroDistribuidora CA','J-55544433-2','0212-555-4433',NULL);
INSERT INTO "sectores" VALUES (1,1,'Centro');
INSERT INTO "tipos_pago" VALUES (1,1,'Efectivo'),
 (2,1,'Transferencia'),
 (3,1,'Tarjeta de Débito'),
 (4,1,'Tarjeta de Crédito'),
 (5,1,'Pago Móvil');
INSERT INTO "usuarios" VALUES (1,'12345678','scrypt:32768:8:1$lHBAWMFWJ2IidHB7$bd878d2d0cd6379049d206be691cf221f8e40fa12ff7c09ab9119c612ea039e340e8c79610b5d826ab2be3ee1e7e8d72d592ad6af5a7bd81410d2c381a69bdee','Encargado',1,'Juan Pérez (Encargado)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 (2,'87654321','scrypt:32768:8:1$tYME3wHYSCjJs1fg$f060029bf3f4cbb29b253eb6ffde11666127bc8e391ff4d7fcc606078e9673467aeb5dfb8a6f4aabee1e77b94fdb20b2ac0472529e8446dcd3530b6f126766e1','Empleado Superior',1,'María García (Emp. Superior)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
 (3,'11223344','scrypt:32768:8:1$EooCC6xwJ0QEFaHa$be2650e9c8ca8c617490009ad519803c52464b3080427388ebea6dbb5ed665dad4107a6b37f9f135ecff216f8e629e7f36a676984dca843b06bd8e2297386ec6','Vendedor',1,'Carlos López (Vendedor)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO "ventas" VALUES (1,1,NULL,'2026-05-14 13:24:54.498822',35.5);
COMMIT;
