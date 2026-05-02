<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Enums\SessionKey;
use App\Http\Middlewares\OnlyOneAdmin;
use flight\Container;
use Leaf\Auth;
use Leaf\Flash;
use Leaf\Http\Session;
use League\OAuth2\Client\Provider\GoogleUser;

Flight::route('GET /oauth2/google', static function (): void {
  $auth = Container::getInstance()->get(Auth::class);
  $query = Flight::request()->query;
  $error = $query->error;
  $code = $query->code;
  $state = $query->state;

  if ($error) {
    Flash::set(['No se pudo iniciar sesión con Google'], SessionKey::ERROR_MESSAGES->name);
    error_log("Error de OAuth2 de Google: $error");
    Flight::redirect('/');

    return;
  }

  if (!$code) {
    $authorizationUrl = $auth->client('google')->getAuthorizationUrl();
    $state = $auth->client('google')->getState();
    Session::set(SessionKey::OAUTH2_STATE->name, $state);
    Flight::redirect($authorizationUrl);

    return;
  }

  if (!$state || ($state !== Session::get(SessionKey::OAUTH2_STATE->name))) {
    Session::remove(SessionKey::OAUTH2_STATE->name);
    Flash::set(['No se pudo iniciar sesión con Google. El estado es inválido'], SessionKey::ERROR_MESSAGES->name);
    Flight::redirect('/');

    return;
  }

  try {
    $token = $auth->client('google')->getAccessToken('authorization_code', [
      'code' => $code,
    ]);

    $resourceOwner = $auth->client('google')->getResourceOwner($token);
    assert($resourceOwner instanceof GoogleUser);

    $auth->fromOAuth([
      'token' => $token,
      'user' => [
        'id' => $resourceOwner->getId(),
        'email' => $resourceOwner->getEmail(),
        'names' => $resourceOwner->getFirstName(),
        'lastnames' => $resourceOwner->getLastName(),
        'avatar' => $resourceOwner->getAvatar(),
        'roles' => json_encode([Role::CLIENT->name]),
      ],
    ]);

    Flight::redirect('/');
  } catch (Throwable $error) {
    Flash::set(["No se pudo iniciar sesión con Google."], SessionKey::ERROR_MESSAGES->name);
    error_log("Error al autenticar con OAuth2 de Google: {$error->getMessage()}");

    Flight::redirect('/');

    return;
  }
});

Flight::route('GET /salir', static function (): void {
  $auth = Container::getInstance()->get(Auth::class);
  $auth->logout();
  Session::remove(SessionKey::OAUTH2_STATE->name);
  Flight::redirect('/');
});

Flight::route('GET /dashboard/register', static function (): void {
  (new OnlyOneAdmin)->handle();
  Flight::render('pages/register');
});

Flight::route('GET /', static fn() => Flight::render('pages/ecommerce/index'));
Flight::route('GET /dashboard', static fn() => Flight::render('pages/dashboard/index'));
