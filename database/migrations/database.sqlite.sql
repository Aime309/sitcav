create table if not exists users (
  id varchar(255) primary key,
  roles varchar(255) not null check (roles like '["%"]'),
  created_at datetime not null default current_timestamp,
  updated_at datetime not null default current_timestamp,
  password varchar(255),
  email varchar(255) not null unique check (email like '%@%')
);
