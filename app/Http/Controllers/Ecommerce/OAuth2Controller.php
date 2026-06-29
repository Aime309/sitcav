<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Error;
use Flight;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Throwable;

final readonly class OAuth2Controller extends Controller
{
  public function google(): void
  {
    try {
      $query = Flight::request()->query;
      $error = $query["error"];
      $code = $query["code"];
      $state = $query["state"];
      $flashState = flash()->display();
      $savedReferrer = flash()->displaySaved();

      if (!$savedReferrer) {
        flash()->save(Flight::request()->referrer);
      }

      $google = new Google(
        [
          "clientId" => $_ENV["GOOGLE_AUTH_CLIENT_ID"],
          "clientSecret" => $_ENV["GOOGLE_AUTH_CLIENT_SECRET"],
          "redirectUri" => explode("?", Flight::request()->getFullUrl())[0],
        ],
        ["httpClient" => new Client(["verify" => false])],
      );

      auth()->withProvider("google", $google);

      if ($error) {
        throw new Error($error);
      }

      if (!$code) {
        $authorizationUrl = $google->getAuthorizationUrl();

        flash()->set($google->getState());
        Flight::redirect($authorizationUrl);

        exit;
      }

      if (!$state || $state !== $flashState) {
        throw new Error("Estado inválido");
      }

      $accessToken = $google->getAccessToken(
        "authorization_code",
        compact("code"),
      );

      if (!$accessToken instanceof AccessToken) {
        throw new Error("Código inválido");
      }

      $resourceOwner = $google->getResourceOwner($accessToken);

      if (!$resourceOwner instanceof GoogleUser) {
        throw new Error("Token inválido");
      }

      if (
        !auth()->fromOAuth([
          "token" => $accessToken,
          "user" => [
            auth()->config("id.key") => $resourceOwner->getId(),
            "email" => $resourceOwner->getEmail(),
            auth()->config("roles.key") => json_encode(["Client"]),
            "avatar" => file_get_contents($resourceOwner->getAvatar() ?: ""),
            "name" => $resourceOwner->getFirstName(),
            "last_name" => $resourceOwner->getLastName(),
          ],
        ])
      ) {
        flash()->set(auth()->errors());
        Flight::redirect($savedReferrer);
      } else {
        Flight::redirect(Flight::getUrl("ecommerce.index"));
      }
    } catch (Throwable $throwable) {
      flash()->set([$throwable->getMessage()]);
      Flight::redirect($savedReferrer);
    } finally {
      flash()->clearSaved();
    }
  }
}
