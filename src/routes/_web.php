<?php

Flight::route('*', function () {
  Flight::render('layouts/base');
});
