<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Flight;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final readonly class IndexController extends Controller
{
  public function __construct(
    private ClientInterface $client,
    private RequestFactoryInterface $requestFactory,
  ) {}

  public function __invoke(): void
  {
    Flight::render('index', [
      'products' => json_decode(
        $this
          ->client
          ->sendRequest($this->requestFactory->createRequest(
            'GET',
            "." . Flight::getUrl('api.products.pinned'),
          ))
          ->getBody()
          ->getContents(),
        true,
      ),
      'trendingItems' => json_decode(
        $this->client->sendRequest($this->requestFactory->createRequest(
          'GET',
          '.' . Flight::getUrl('api.products.trending')
        ))
          ->getBody()
          ->getContents(),
        true,
      ),
    ]);
  }
}
