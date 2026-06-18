<?php

declare(strict_types=1);

use Leaf\Exception\Handler\PrettyPageHandler;
use Leaf\Exception\Run;

Flight::group(
  "/dashboard",
  static function (): void {
    Flight::route("GET /(login|register)", static function (): void {
      Flight::render("index");
    });

    Flight::route("GET /*", static function (): void {
      Flight::render("index");
    })->addMiddleware(static function (): void {
      if (auth()->user() === null) {
        Flight::redirect("/dashboard/login");

        exit();
      }
    });
  },
  [
    static function (): void {
      $run = new Run();
      $prettyPushHandler = new PrettyPageHandler();

      $prettyPushHandler
        ->addEditor(PrettyPageHandler::EDITOR_SUBLIME, "subl:%file:%line")
        ->setEditor(PrettyPageHandler::EDITOR_SUBLIME);

      $run->pushHandler($prettyPushHandler)->register();
    },
    static function (): void {
      Flight::set(
        "flight.views.path",
        "views/dashboardkit-free-admin-template/react/dist",
      );

      Flight::set("flight.views.extension", ".html");
    },
  ],
);
