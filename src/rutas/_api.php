<?php

use SITCAV\Controladores\API\ControladorDePerfil;

Flight::group('/api', function (): void {
  Flight::route('GET /perfil', [ControladorDePerfil::class, 'obtenerPerfil']);
  Flight::route("POST /registrarse", [ControladorDePerfil::class, 'procesarRegistro']);
});
