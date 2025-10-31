-- NO CAMBIAR EL ÓRDEN
drop table if exists eventos;
drop table if exists pagos;
drop table if exists detalles_venta;
drop table if exists ventas;
drop table if exists clientes;
drop table if exists productos;
drop table if exists detalles_compra;
drop table if exists compras;
drop table if exists proveedores;
drop table if exists sectores;
drop table if exists localidades;
drop table if exists estados;
drop table if exists cotizaciones;
drop table if exists tipos_pago;
drop table if exists categorias;
drop table if exists negocios;
drop table if exists marcas;
drop table if exists usuarios;

create table usuarios (
  id integer primary key autoincrement,
  email varchar(255) unique check (email like '%@%'),
  cedula integer unique check (cedula > 0),
  clave_encriptada varchar(255),
  roles varchar(255) not null check (roles like '["%"]'),
  esta_despedido boolean default false,
  pregunta_secreta varchar(255),
  respuesta_secreta_encriptada varchar(255),
  url_imagen varchar(255),
  id_encargado integer,

  foreign key (id_encargado) references usuarios(id)
);

create table cotizaciones (
  id integer primary key autoincrement,
  fecha_hora_creacion datetime unique default current_timestamp,
  tasa_bcv decimal(10, 2) not null check (tasa_bcv > 0),
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id)
);

create table estados (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id),
  unique (nombre, id_encargado)
);

create table localidades (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_estado integer not null,

  foreign key (id_estado) references estados(id),
  unique (nombre, id_estado)
);

create table sectores (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_localidad integer not null,

  foreign key (id_localidad) references localidades(id),
  unique (nombre, id_localidad)
);

create table negocios (
  id integer primary key autoincrement,
  rif varchar(255) not null unique check (rif like 'J-%' or rif like 'V-%' or rif like 'E-%'),
  nombre varchar(255) not null,
  telefono varchar(255) check (telefono like '+%'),
  id_localidad integer not null,
  id_sector integer,

  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id)
);

create table proveedores (
  id integer primary key autoincrement,
  rif varchar(255) not null unique check (rif like 'J-%' or rif like 'V-%' or rif like 'E-%'),
  nombre varchar(255) not null,
  telefono varchar(255) check (telefono like '+%'),
  id_estado integer not null,
  id_localidad integer,
  id_sector integer,

  foreign key (id_estado) references estados(id),
  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id)
);

create table categorias (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id),
  unique (nombre, id_encargado)
);

create table marcas (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  url_imagen varchar(255) unique,
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id),
  unique (nombre, id_encargado)
);

create table productos (
  id integer primary key autoincrement,
  codigo varchar(255),
  nombre varchar(255) not null,
  descripcion text,
  url_imagen varchar(255),
  precio_unitario_actual_dolares decimal(10, 2) not null check (precio_unitario_actual_dolares > 0),
  precio_unitario_actual_bcv decimal(10, 2) not null check (precio_unitario_actual_bcv > 0),
  cantidad_disponible integer not null check (cantidad_disponible >= 0),
  dias_garantia integer check (dias_garantia >= 0),
  dias_apartado integer not null check (dias_apartado >= 0),
  id_categoria integer not null,
  id_proveedor integer not null,
  id_marca integer not null,

  foreign key (id_categoria) references categorias_producto(id),
  foreign key (id_proveedor) references proveedores(id),
  foreign key (id_marca) references marcas(id)
);

create table compras (
  id integer primary key autoincrement,
  fecha_hora_creacion datetime unique default current_timestamp,
  tasa_bcv decimal(10, 2) not null check (tasa_bcv > 0),
  id_proveedor integer not null,

  foreign key (id_proveedor) references proveedores(id)
);

create table detalles_compra (
  id integer primary key autoincrement,
  precio_unitario_fijo_dolares decimal(10, 2) not null check (precio_unitario_fijo_dolares > 0),
  precio_unitario_fijo_bcv decimal(10, 2) not null check (precio_unitario_fijo_bcv > 0),
  cantidad integer not null check (cantidad > 0),
  id_compra integer not null,
  id_producto integer not null,

  foreign key (id_compra) references compras(id),
  foreign key (id_producto) references productos(id)
);

create table clientes (
  id integer primary key autoincrement,
  cedula integer not null check (cedula > 0),
  nombres varchar(255) not null,
  apellidos varchar(255) not null,
  telefono varchar(255) check (telefono like '+%'),
  id_localidad integer not null,
  id_sector integer,

  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id),
  unique (cedula, id_localidad),
  unique (nombres, apellidos, id_localidad)
);

create table ventas (
  id integer primary key autoincrement,
  fecha_hora_creacion datetime unique default current_timestamp,
  id_cliente integer not null,

  foreign key (id_cliente) references clientes(id)
);

create table detalles_venta (
  id integer primary key autoincrement,
  cantidad integer not null check (cantidad > 0),
  precio_unitario_fijo_dolares decimal(10, 2) not null check (precio_unitario_fijo_dolares > 0),
  precio_unitario_fijo_bcv decimal(10, 2) not null check (precio_unitario_fijo_bcv > 0),
  esta_apartado boolean default false,
  id_venta integer not null,
  id_producto integer not null,

  foreign key (id_venta) references ventas(id),
  foreign key (id_producto) references productos(id)
);

create table tipos_pago (
  id integer primary key autoincrement,
  nombre varchar(255) not null,
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id),
  unique (nombre, id_encargado)
);

create table pagos (
  id integer primary key autoincrement,
  fecha_hora_creacion datetime default current_timestamp,
  tasa_bcv decimal(10, 2) not null check (tasa_bcv > 0),
  monto_dolares decimal(10, 2) not null check (monto_dolares > 0),
  id_tipo_pago integer not null,
  id_detalle_venta integer not null,

  foreign key (id_tipo_pago) references tipos_pago(id),
  foreign key (id_detalle_venta) references detalles_ventas(id)
);

create table eventos (
  id integer primary key autoincrement,
  fecha_hora_creacion datetime default current_timestamp,
  tipo varchar(255) not null check (tipo in ('Contrato', 'Despido', 'Ascenso')),
  tabla varchar(255) not null check (tabla in ('usuarios')),
  id_entidad varchar(255) not null,
  id_encargado integer not null,

  foreign key (id_encargado) references usuarios(id)
);
