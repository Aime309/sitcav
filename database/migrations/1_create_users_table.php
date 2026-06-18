<?php

declare(strict_types=1);

db()
  ->createTableIfNotExists((string) auth()->config("db.table"), [
    auth()->config("id.key") => "TEXT PRIMARY KEY NOT NULL",
    auth()->config("roles.key") => sprintf(
      "TEXT NOT NULL CHECK (%s LIKE '[\"%s\"]')",
      auth()->config("roles.key"),
      '%',
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
    auth()->config("password.key") => sprintf(
      "TEXT NOT NULL UNIQUE DEFAULT '' CHECK (LENGTH(%s) >= 0)",
      auth()->config("password.key")
    ),
    "email" => "TEXT NOT NULL UNIQUE CHECK (email LIKE '%@%')",
    "name" => "TEXT NOT NULL DEFAULT ''",
    "last_name" => "TEXT NOT NULL DEFAULT ''",
    "avatar" => "BLOB NOT NULL UNIQUE DEFAULT ''",
    "phone" => "TEXT NOT NULL DEFAULT ''",
    "location" => "TEXT NOT NULL DEFAULT ''",
  ])
  ->execute();

if ($_ENV["APP_ENV"] === "local") {
  auth()->register([
    auth()->config("id.key") => uniqid(),
    auth()->config("roles.key") => json_encode(["Client"]),
    auth()->config("password.key") => "test",
    "email" => "test@test.com",
  ]);
}
