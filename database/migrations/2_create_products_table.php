<?php

declare(strict_types=1);

db()
  ->createTableIfNotExists("products", [
    auth()->config("id.key") => "TEXT PRIMARY KEY NOT NULL",
    "created_at" => "DATETIME"
      . " NOT NULL"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (created_at LIKE '____-__-__ __:__:__')",
    "updated_at" => "DATETIME"
      . " NOT NULL"
      . " DEFAULT CURRENT_TIMESTAMP"
      . " CHECK (updated_at LIKE '____-__-__ __:__:__' AND updated_at >= created_at)",
    "sources" => 'TEXT NOT NULL DEFAULT \'[]\' CHECK (sources LIKE \'[%]\')',
    "name" => "TEXT NOT NULL CHECK (LENGTH(name) > 0)",
    "price" => "REAL NOT NULL CHECK (price > 0)",
    "description" => "TEXT NOT NULL DEFAULT ''",
    "type" => "TEXT NOT NULL DEFAULT ''",
    "category" => "TEXT NOT NULL DEFAULT ''",
    "pinned" => "BOOLEAN NOT NULL DEFAULT FALSE",
  ])
  ->execute();

const PRODUCTS = [
  [
    "sources" => [
      "./img/product/product_list_1.png",
      "./img/product/single_product.png",
    ],
    "name" => "Cervical pillow for airplane car office nap pillow",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_2.png",
      "./img/product/single_product.png",
    ],
    "name" => "Geometric striped flower home classy decor",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_3.png",
      "./img/product/single_product.png",
    ],
    "name" => "Foam filling cotton slow rebound pillows",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_4.png",
      "./img/product/single_product.png",
    ],
    "name" => "Memory foam filling cotton Slow rebound pillows",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_5.png",
      "./img/product/single_product.png",
    ],
    "name" => "Memory foam filling cotton Slow rebound pillows",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_6.png",
      "./img/product/single_product.png",
    ],
    "name" => "Sleeping orthopedic sciatica Back Hip Joint Pain relief",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_7.png",
      "./img/product/single_product.png",
    ],
    "name" => "Memory foam filling cotton Slow rebound pillows",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_8.png",
      "./img/product/single_product.png",
    ],
    "name" => "Sleeping orthopedic sciatica Back Hip Joint Pain relief",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_9.png",
      "./img/product/single_product.png",
    ],
    "name" => "Geometric striped flower home classy decor",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => [
      "./img/product/product_list_10.png",
      "./img/product/single_product.png",
    ],
    "name" => "Geometric striped flower home classy decor",
    "price" => 5,
    "description" =>
      "Seamlessly empower fully researched growth strategies and interoperable internal or “organic” sources. Credibly innovate granular internal or “organic” sources whereas high standards in web-readiness. Credibly innovate granular internal or organic sources whereas high standards in web-readiness. Energistically scale future-proof core competencies vis-a-vis impactful experiences. Dramatically synthesize integrated schemas. with optimal networks.",
    "type" => "Pillows",
  ],
  [
    "sources" => ["./img/arrivel/arrivel_1.png"],
    "name" => "Minimalistic shop for multipurpose use",
    "price" => 360,
    "type" => "Clothing",
  ],
  [
    "sources" => ["./img/arrivel/arrivel_2.png"],
    "name" => "Minimalistic shop for multipurpose use",
    "price" => 360,
    "type" => "Clothing",
  ],
  [
    "sources" => ["./img/single_product_1.png"],
    "name" => "Printed memory foam brief modern throw pillow case",
    "price" => 10,
    "type" => "Pillows",
    "pinned" => true,
  ],
  [
    "sources" => ["./img/single_product_2.png"],
    "name" => "Printed memory foam brief modern throw pillow case",
    "price" => 10,
    "type" => "Pillows",
    "pinned" => true,
  ],
  [
    "sources" => ["./img/single_product_3.png"],
    "name" => "Printed memory foam brief modern throw pillow case",
    "price" => 10,
    "type" => "Pillows",
    "pinned" => true,
  ],
  [
    "name" => "Fresh Blackberry",
    "price" => 360,
    "type" => "Food",
  ],
  [
    "name" => "Fresh Tomatoes",
    "price" => 360,
    "type" => "Food",
  ],
  [
    "name" => "Fresh Brocoli",
    "price" => 360,
    "type" => "Food",
  ],
  [
    "name" => "HeadPhone",
    "sources" => ["/views/dashboardkit-free-admin-template/react/src/assets/images/widget/p1.png"],
    "price" => 10,
    "type" => "Tech",
  ],
  [
    "name" => "Iphone 6",
    "sources" => ["/views/dashboardkit-free-admin-template/react/src/assets/images/widget/p2.png"],
    "price" => 10,
    "type" => "Tech",
  ],
  [
    "name" => "Jacket",
    "sources" => ["/views/dashboardkit-free-admin-template/react/src/assets/images/widget/p3.png"],
    "price" => 10,
    "type" => "Clothing",
  ],
  [
    "name" => "Sofa",
    "sources" => ["/views/dashboardkit-free-admin-template/react/src/assets/images/widget/p4.png"],
    "price" => 10,
    "type" => "Furniture",
  ],
];

foreach (PRODUCTS as $product) {
  db()
    ->insert("products")
    ->unique(auth()->config("id.key"))
    ->params([
      auth()->config("id.key") => uniqid(),
      "sources" => json_encode($product["sources"] ?? []),
      "name" => $product["name"],
      "price" => $product["price"],
      "description" => $product["description"] ?? "",
      "type" => $product["type"] ?? '',
      "pinned" => $product["pinned"] ?? false,
    ])
    ->execute();
}
