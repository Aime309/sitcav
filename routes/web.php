<?php

declare(strict_types=1);

Flight::route('GET /', static fn() => Flight::render('pages/index'));
