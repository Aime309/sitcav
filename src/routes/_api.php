<?php

Flight::group('/api', function (): void {
  Flight::route('GET /perfil', function (): void {
    Flight::json([
      'id' => auth()->id(),
      'idCard' => auth()->user()->idCard
    ]);
  });
});
