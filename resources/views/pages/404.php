<?php

http_response_code(404);

Flight::render('layouts/error', [
  'title' => 'Página no encontrada',
  'errorText' => 'La ruta que intentaste abrir no existe o ya no esta disponible.',
]);
