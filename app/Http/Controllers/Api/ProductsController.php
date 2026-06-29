<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Flight;

final readonly class ProductsController extends Controller
{
  public function index(): void
  {
    Flight::json(
      array_map(
        static fn(array $product): array => [
          'sources' => json_decode($product['sources']),
          'pinned' => (bool) $product['pinned'],
        ] + $product,
        db()->select('products')->all(),
      ),
    );
  }

  public function indexPinned(): void
  {
    Flight::json(
      array_map(
        static fn(array $product): array => [
          'src' => json_decode($product['sources'])[0],
          'pinned' => (bool) $product['pinned'],
        ] + $product,
        db()->select('products')->where('pinned', true)->all(),
      ),
    );
  }

  public function indexTypes(): void
  {
    Flight::json(
      array_filter(
        array_map(
          static fn(array $product): string => $product['type'],
          db()->select('products', 'DISTINCT type')->all(),
        ),
      ),
    );
  }

  public function indexCategories(): void
  {
    Flight::json(
      array_filter(
        array_map(
          static fn(array $product): string => $product['category'],
          db()->select('products', 'DISTINCT category')->all(),
        ),
      ),
    );
  }

  public function indexTrending(): void
  {
    Flight::json([
      [
        "src" => "./img/tranding_item/tranding_item_1.png",
        "name" => "Cervical pillow for airplane car office nap pillow",
        "price" => 5,
      ],
      [
        "src" => "./img/tranding_item/tranding_item_2.png",
        "name" => "Foam filling cotton slow rebound pillows",
        "price" => 5,
      ],
      [
        "src" => "./img/tranding_item/tranding_item_3.png",
        "name" => "Memory foam filling cotton Slow rebound pillows",
        "price" => 5,
      ],
      [
        "src" => "./img/tranding_item/tranding_item_4.png",
        "name" => "Cervical pillow for airplane car office nap pillow",
        "price" => 5,
      ],
      [
        "src" => "./img/tranding_item/tranding_item_5.png",
        "name" => "Foam filling cotton slow rebound pillows",
        "price" => 5,
      ],
      [
        "src" => "./img/tranding_item/tranding_item_6.png",
        "name" => "Memory foam filling cotton Slow rebound pillows",
        "price" => 5,
      ],
    ]);
  }

  public function show(string $id): void
  {
    $product = db()->select('products')->find($id);
    $product['sources'] = json_decode($product['sources']);
    $product['stock'] = 10;

    Flight::json($product);
  }
}
