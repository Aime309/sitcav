drop table if exists clientes;
drop table if exists sectores;
drop table if exists localidades;
drop table if exists estados;
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

create table estados (
  id integer primary key autoincrement,
  nombre varchar(255) not null unique
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

create table clientes (
  id integer primary key autoincrement,
  cedula integer not null unique check (cedula > 0),
  nombres varchar(255) not null,
  apellidos varchar(255) not null,
  telefono varchar(255) check (telefono like '0__________' or telefono like '+%'),
  id_estado integer not null,
  id_localidad integer not null,
  id_sector integer not null,
  id_usuario integer not null,

  foreign key (id_estado) references estados(id),
  foreign key (id_localidad) references localidades(id),
  foreign key (id_sector) references sectores(id),
  foreign key (id_usuario) references usuarios(id)

);

insert into estados (id, nombre) values
  (1, 'Mérida'),
  (2, 'Trujillo'),
  (3, 'Táchira'),
  (4, 'Zulia');

insert into localidades (id, nombre, id_estado) values
  (1, 'El Pinar', 1),
  (2, 'Tucaní', 1),
  (3, 'Caja Seca', 4),
  (4, 'La Fría', 4);

insert into sectores (id, nombre, id_localidad) values
  (1, 'La Batea', 1),
  (2, 'La Conquista', 3);
