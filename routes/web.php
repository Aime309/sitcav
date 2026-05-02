<?php

declare(strict_types=1);

Flight::route('GET /', static fn() => Flight::render('pages/index'));
Flight::route('GET /dashboard', static fn() => Flight::render('pages/index'));
