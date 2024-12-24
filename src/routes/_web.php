<?php

function renderSvelte(): void {
  Flight::route('*', function (): void {
    Flight::render('layouts/base');
  });
}

Flight::route('POST /ingresar', function (): void {
  $credentials = Flight::request()->data->getData();
  $wasLoggedSuccessfully = auth()->login($credentials);

  if ($wasLoggedSuccessfully) {
    Flight::redirect('/panel');
  } else {
    dd(auth()->errors());
  }
});

Flight::route('/salir', function (): void {
  auth()->logout();
  Flight::redirect('/');
});

Flight::group('/panel/', function (): void {
  renderSvelte();
}, [function () {
  if (auth()->id() === null) {
    Flight::redirect('/ingresar');
  } else {
    return true;
  }
}]);

renderSvelte();
