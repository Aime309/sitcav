drop table if exists customers;
drop table if exists sectors;
drop table if exists locations;
drop table if exists states;
drop table if exists users;

create table users (
  id integer primary key autoincrement,
  idCard integer not null unique check (idCard > 0),
  password varchar(255) not null,
  role varchar(255) not null check (role in ("admin", "vendedor")),
  is_active boolean default true,
  secret_question varchar(255) not null,
  secret_answer varchar(255) not null,
  admin_id integer,

  foreign key (admin_id) references users(id)
);

create table states (
  id integer primary key autoincrement,
  name varchar(255) not null unique
);

create table locations (
  id integer primary key autoincrement,
  name varchar(255) not null,
  state_id integer not null,

  foreign key (state_id) references states(id)
);

create table sectors (
  id integer primary key autoincrement,
  name varchar(255) not null,
  location_id integer not null,

  foreign key (location_id) references locations(id)
);

create table customers (
  id integer primary key autoincrement,
  idCard integer not null unique check (idCard > 0),
  names varchar(255) not null,
  lastNames varchar(255) not null,
  phone varchar(255) check (phone like "0__________" or phone like "+%"),
  state_id integer not null,
  location_id integer not null,
  sector_id integer not null,
  user_id integer not null,

  foreign key (state_id) references states(id),
  foreign key (location_id) references locations(id),
  foreign key (sector_id) references sectors(id),
  foreign key (user_id) references users(id)

);

insert into states (id, name) values
  (1, 'Mérida'),
  (2, 'Trujillo'),
  (3, 'Táchira'),
  (4, 'Zulia');

insert into locations (id, name, state_id) values
  (1, 'El Pinar', 1),
  (2, 'Tucaní', 1),
  (3, 'Caja Seca', 4),
  (4, 'La Fría', 4);

insert into sectors (id, name, location_id) values
  (1, 'La Batea', 1),
  (2, 'La Conquista', 3);
