<?php

declare(strict_types=1);

db()
  ->createTableIfNotExists("reservations", [
    auth()->config("id.key") => "TEXT PRIMARY KEY NOT NULL",
    "user_id" => sprintf(
      "TEXT NOT NULL REFERENCES %s(%s)",
      auth()->config("db.table"),
      auth()->config("id.key")
    ),
    "created_at" => "DATETIME"
      . " NOT NULL"
      . " UNIQUE"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (created_at LIKE '____-__-__ __:__:__')",
    "updated_at" => "DATETIME"
      . " NOT NULL"
      . " UNIQUE"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (updated_at LIKE '____-__-__ __:__:__' AND updated_at >= created_at)",
  ])
  ->execute();

db()
  ->createTableIfNotExists("reservation_details", [
    auth()->config("id.key") => "TEXT PRIMARY KEY NOT NULL",
    "user_id" => sprintf(
      "TEXT NOT NULL REFERENCES %s(%s)",
      auth()->config("db.table"),
      auth()->config("id.key")
    ),
    "created_at" => "DATETIME"
      . " NOT NULL"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (created_at LIKE '____-__-__ __:__:__')",
    "updated_at" => "DATETIME"
      . " NOT NULL"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (updated_at LIKE '____-__-__ __:__:__' AND updated_at >=
          created_at)",
    "reservation_id" => sprintf(
      "TEXT NOT NULL REFERENCES reservations(%s)",
      auth()->config("id.key"),
    ),
    "product_id" => sprintf(
      "TEXT NOT NULL REFERENCES products(%s)",
      auth()->config("id.key"),
    ),
    "quantity" => "INTEGER NOT NULL CHECK (quantity > 0)",
  ])
  ->execute();
