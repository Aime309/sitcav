<?php

http_response_code(500);

Flight::render('layouts/error', [
  'title' => 'Error interno del servidor',
  'errorText' => 'Ocurrio un error inesperado mientras procesabamos tu solicitud. Intenta nuevamente en unos minutos.',
]);
