<?php

declare(strict_types=1);

use Leaf\Helpers\Password;

auth()->autoConnect();

auth()->config(
  "password.encode",
  static fn(string $password): string => Password::hash(
    $password,
    Password::BCRYPT,
    ["cost" => 12],
  ),
);

auth()->config(
  "token.secret",
  Password::hash(uniqid(), Password::BCRYPT, [
    "cost" => $_ENV["BCRYPT_ROUNDS"],
  ]),
);

auth()->config("messages.loginParamsError", "¡Credenciales incorrectas!");

auth()->createRoles([
  "Administrator" => [],
  "Mandated" => [],
  "Seller" => [],
  "Client" => [],
]);
