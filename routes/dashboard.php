<?php

declare(strict_types=1);

use App\Http\Middleware\Dashboard\Authenticate;
use App\Http\Middleware\Dashboard\Authorize;
use App\Http\Middleware\Dashboard\RedirectIfAuthenticated;
use Leaf\Exception\Handler\PrettyPageHandler;
use Leaf\Exception\Run;

Flight::group(
  '/dashboard',
  static function (): void {
    Flight::router()->get('/login', static function (): void {
      Flight::render('index');
    })
      ->addMiddleware(RedirectIfAuthenticated::class)
      ->setAlias('dashboard.login');

    Flight::router()->get('/register', static function (): void {
      Flight::render('index');
    })
      ->addMiddleware(RedirectIfAuthenticated::class)
      ->setAlias('dashboard.register');

    Flight::router()->get('/logout', static function (): void {
      auth()->logout();
      Flight::redirect(Flight::getUrl('dashboard.login'));
    })->setAlias('dashboard.logout');

    Flight::router()->get('/*', static function (): void {
      Flight::render('index');
    })
      ->addMiddleware(Authenticate::class)
      ->addMiddleware(Authorize::class)
      ->setAlias('dashboard.index');
  },
  [
    static function (): void {
      $run = new Run();
      $prettyPushHandler = new PrettyPageHandler();

      $prettyPushHandler
        ->addEditor(PrettyPageHandler::EDITOR_SUBLIME, 'subl:%file:%line')
        ->setEditor(PrettyPageHandler::EDITOR_SUBLIME);

      $run->pushHandler($prettyPushHandler)->register();
    },
    static function (): void {
      Flight::set(
        'flight.views.path',
        'views/dashboardkit-free-admin-template/react/dist',
      );

      Flight::set('flight.views.extension', '.html');
    },
  ],
);
