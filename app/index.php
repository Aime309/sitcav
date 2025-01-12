<?php

use Symfony\Component\Dotenv\Dotenv;

/** Absolute path to project root */
const ROOT = __DIR__ . '/..';

require ROOT . '/vendor/autoload.php';

(new Dotenv)->load(ROOT . '/.env');
auth()->config('session', true);
auth()->config('messages.loginParamsError', 'Cédula o contraseña incorrecta');
auth()->config('messages.loginPasswordError', auth()->config('messages.loginParamsError'));

Flight::set('flight.views.path', ROOT . '/src/views');

require ROOT . '/src/routes/index.php';
Flight::start();
