<?php

Flight::group('/api', function (): void {
  Flight::route('GET /perfil', function (): void {
    Flight::json([
      'id' => auth()->id(),
      'idCard' => auth()->user()->idCard
    ]);
  });

  Flight::route("POST /registrarse", function () {
    $userdata = Flight::request()->data;

    auth()->register([
      'idCard' => $userdata->idCard,
      'password' => password_hash($userdata->password, PASSWORD_DEFAULT),
      'role' => "admin",
      'secret_question' => $userdata->secret_question,
      'secret_answer' => password_hash($userdata->secret_answer, PASSWORD_DEFAULT),
    ]);

    auth()->login([
      'idCard' => $userdata->idCard,
      'password' => $userdata->password
    ]);

    Flight::json(auth()->data());
  });
});
