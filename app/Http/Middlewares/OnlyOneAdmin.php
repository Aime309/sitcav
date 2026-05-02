<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Enums\SessionKey;
use App\Enums\Role;
use flight\Container;
use Leaf\Auth;
use Leaf\Flash;

final class OnlyOneAdmin
{
  public function __invoke(): void
  {
    $this->handle();
  }

  public function handle(): void
  {
    $auth = Container::getInstance()->get(Auth::class);

    foreach ($auth->db()->select('users')->all() as $user) {
      if (!str_contains(strval($user['roles'] ?? ''), Role::ADMIN->name)) {
        continue;
      }

      if (strtoupper(strval(\Flight::request()->method)) === 'GET') {
        Flash::set(['El registro inicial ya no está disponible porque ya existe un administrador.'], SessionKey::ERROR_MESSAGES->name);
        \Flight::redirect('/dashboard');
        return;
      }

      \Flight::jsonHalt([
        'success' => false,
        'message' => 'El registro inicial ya no está disponible.',
      ], 403);
    }
  }
}
