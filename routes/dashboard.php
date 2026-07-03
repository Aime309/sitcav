<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\IndexController;
use App\Http\Middleware\Dashboard\Authenticate;
use App\Http\Middleware\Dashboard\Authorize;
use App\Http\Middleware\Dashboard\RedirectIfAuthenticated;
use Leaf\Exception\Handler\PrettyPageHandler;
use Leaf\Exception\Run;

Flight::group(
  '/dashboard',
  static function (): void {
    Flight::group('/login', static function (): void {
      Flight::router()->get('/', [IndexController::class, '__invoke'])->setAlias('dashboard.login');

      Flight::post('/', static function (): void {
        $data = Flight::request()->data;

        if ($data['remember']) {
          auth()->config('session.lifetime', 0);
        }

        if (auth()->login([
          'email' => $data['email'],
          auth()->config('password.key') => $data['password'],
        ])) {
          Flight::redirect(Flight::getUrl('dashboard.index'));

          return;
        }

        Flight::redirect(Flight::getUrl("dashboard.login"));
      });
    }, [RedirectIfAuthenticated::class]);

    Flight::group('/register', static function (): void {
      Flight::router()->get('/', [IndexController::class, '__invoke'])->setAlias('dashboard.register');

      Flight::post('/', static function (): void {
        $data = Flight::request()->data->getData();

        if (auth()->register([
          auth()->config('id.key') => uniqid(),
          'name' => $data['name'],
          'email' => $data['email'],
          auth()->config('password.key') => $data['password'],
          auth()->config('roles.key') => json_encode(['Administrator']),
        ])) {
          Flight::redirect(Flight::getUrl('dashboard.index'));

          return;
        }

        Flight::redirect(Flight::getUrl('dashboard.register'));
      });
    }, [RedirectIfAuthenticated::class]);

    Flight::router()->get('/logout', static function (): void {
      auth()->logout();
      Flight::redirect(Flight::getUrl('dashboard.login'));
    })->setAlias('dashboard.logout');

    Flight::router()->get('(/@page:.+)', [IndexController::class, '__invoke'])
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
