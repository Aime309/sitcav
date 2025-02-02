-- NO CAMBIAR EL ÓRDEN
drop table if exists pagos;
drop table if exists detalles_ventas;
drop table if exists ventas;
drop table if exists clientes;
drop table if exists productos;
drop table if exists detalles_compras;
drop table if exists compras;
drop table if exists proveedores;
drop table if exists sectores;
drop table if exists localidades;
drop table if exists estados;
drop table if exists cotizaciones;
drop table if exists tipos_pago;
drop table if exists categorias_producto;
drop table if exists negocios;
drop table if exists usuarios;

create table usuarios (
  id integer primary key autoincrement,
  cedula integer not null unique check (cedula > 0),
  clave varchar(255) not null,
  rol varchar(255) not null check (rol in ('Administrador', 'Vendedor')),
  esta_activo boolean default true,
  pregunta_secreta varchar(255) not null,
  respuesta_secreta varchar(255) not null,
  id_admin integer,

  foreign key (id_admin) references usuarios(id)
);

create table cotizaciones (
  id integer primary key autoincrement,
  fecha_hora datetime default current_timestamp,
  tasa_dolar_bolivares decimal(10, 2) not null check (tasa_dolar_bolivares > 0),
  id_usuario integer not null,

  foreign key (id_usuario) references usuarios(id)
);

create table estados (
  id integer primary key autoincrement,
  nombre varchar(255) not null unique,
  id_usuario integer not null,

  foreign key (id_usuario) references usuarios(id)
);

create table localidades (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_estado integer not null,

  foreign key (id_estado) references estados(id)
);

create table sectores (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_localidad integer not null,

  foreign key (id_localidad) references localidades(id)
);

create table negocios (
  id integer primary key autoincrement,
  rif varchar(255) not null unique check (rif like 'J-%' or rif like 'V-%' or rif like 'E-%'),
  nombre varchar(255) not null,
  telefono varchar(255) check (telefono like '0__________' or telefono like '+%'),
  id_localidad integer not null,
  id_sector integer,

  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id)
);

create table proveedores (
  id integer primary key autoincrement,
  rif varchar(255) not null unique check (rif like 'J-%' or rif like 'V-%' or rif like 'E-%'),
  nombre varchar(255) not null,
  telefono varchar(255) check (telefono like '0__________' or telefono like '+%'),
  id_estado integer not null,
  id_localidad integer,
  id_sector integer,

  foreign key (id_estado) references estados(id),
  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id)
);

create table categorias_producto (
  id integer primary key autoincrement,
  nombre varchar(255) not null unique,
  id_usuario integer not null,

  foreign key (id_usuario) references usuarios(id)
);

create table productos (
  id integer primary key autoincrement,
  codigo varchar(255) unique,
  nombre varchar(255) not null,
  descripcion text,
  url_imagen varchar(255),
  precio_unitario_actual_dolares decimal(10, 2) not null check (precio_unitario_actual_dolares > 0),
  cantidad_disponible integer not null check (cantidad_disponible >= 0),
  dias_garantia integer check (dias_garantia >= 0),
  dias_apartado integer not null check (dias_apartado >= 0),
  id_categoria integer not null,
  id_proveedor integer not null,

  foreign key (id_categoria) references categorias_producto(id),
  foreign key (id_proveedor) references proveedores(id)
);

create table compras (
  id integer primary key autoincrement,
  fecha_hora datetime default current_timestamp,
  cotizacion_dolar_bolivares decimal(10, 2) not null check (cotizacion_dolar_bolivares > 0),
  id_proveedor integer not null,

  foreign key (id_proveedor) references proveedores(id)
);

create table detalles_compras (
  id integer primary key autoincrement,
  cantidad integer not null check (cantidad > 0),
  precio_unitario_fijo_dolares decimal(10, 2) not null check (precio_unitario_fijo_dolares > 0),
  id_producto integer not null,
  id_compra integer not null,

  foreign key (id_producto) references productos(id),
  foreign key (id_compra) references compras(id)
);

create table clientes (
  id integer primary key autoincrement,
  cedula integer not null unique check (cedula > 0),
  nombres varchar(255) not null,
  apellidos varchar(255) not null,
  telefono varchar(255) check (telefono like '0__________' or telefono like '+%'),
  id_localidad integer not null,
  id_sector integer,

  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id)
);

create table ventas (
  id integer primary key autoincrement,
  fecha_hora datetime default current_timestamp,
  id_cliente integer not null,

  foreign key (id_cliente) references clientes(id)
);

create table detalles_ventas (
  id integer primary key autoincrement,
  cantidad integer not null check (cantidad > 0),
  precio_unitario_fijo_dolares decimal(10, 2) not null check (precio_unitario_fijo_dolares > 0),
  esta_apartado boolean default false,
  id_producto integer not null,
  id_venta integer not null,

  foreign key (id_producto) references productos(id),
  foreign key (id_venta) references ventas(id)
);

create table tipos_pago (
  id integer primary key autoincrement,
  nombre varchar(255) not null unique,
  id_usuario integer not null,

  foreign key (id_usuario) references usuarios(id)
);

create table pagos (
  id integer primary key autoincrement,
  fecha_hora datetime default current_timestamp,
  cotizacion_dolar_bolivares decimal(10, 2) not null check (cotizacion_dolar_bolivares > 0),
  monto decimal(10, 2) not null check (monto > 0),
  id_tipo_pago integer not null,
  id_detalle_venta integer not null,

  foreign key (id_tipo_pago) references tipos_pago(id),
  foreign key (id_detalle_venta) references detalles_ventas(id)
);
