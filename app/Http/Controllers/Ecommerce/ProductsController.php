<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Flight;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final readonly class ProductsController extends Controller
{
  public function __construct(
    private ClientInterface $client,
    private RequestFactoryInterface $requestFactory,
  ) {}

  public function index(): void
  {
    Flight::render("product_list", [
      "products" => json_decode(
        $this->client->sendRequest($this->requestFactory->createRequest(
          'GET',
          '.' . Flight::getUrl('api.products')
        ))
          ->getBody()
          ->getContents(),
        true,
      ),
      "categories" => json_decode(
        $this->client->sendRequest($this->requestFactory->createRequest(
          'GET',
          '.' . Flight::getUrl('api.products.categories'),
        ))
          ->getBody()
          ->getContents(),
        true,
      ),
      "types" => json_decode(
        $this->client->sendRequest($this->requestFactory->createRequest(
          'GET',
          '.' . Flight::getUrl('api.products.types'),
        ))
          ->getBody()
          ->getContents(),
        true,
      ),
    ]);
  }

  public function show(string $id): void
  {
    $product = json_decode(
      $this->client->sendRequest($this->requestFactory->createRequest(
        'GET',
        Flight::getUrl('api.products.@id', compact('id')),
      ))
        ->getBody()
        ->getContents(),
      true
    );

    Flight::render('single-product', compact('product'));
  }
}
