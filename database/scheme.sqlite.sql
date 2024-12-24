drop table if exists users;

create table users (
  id integer primary key autoincrement,
  idCard integer not null unique check (idCard > 0),
  password varchar(255) not null
);

insert into users values (1, 12345678, '$2y$10$9ztpfe1YGsKRcLNGhnVi/.lOP33zAWEfbWBUnilJgn1NMqDxHqzfe')
