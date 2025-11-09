<?php

use SITCAV\Enums\ClaveSesion;

Flight::group('/ajax', static function (): void {
  Flight::group('/ajustes', static function (): void {
    Flight::route('POST /tema', static function (): void {
      $data = Flight::request()->data;

      $tema = $data->tema ?: session()->get(ClaveSesion::UI_TEMA->name, 'light');
      $temaColores = $data->tema_colores ?: session()->get(ClaveSesion::UI_COLORES->name, 'Blue_Theme');
      $direccion = $data->direccion ?: session()->get(ClaveSesion::UI_DIRECCION->name, 'ltr');
      $layout = $data->layout ?: session()->get(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, 'vertical');
      $container = $data->container ?: session()->get(ClaveSesion::UI_ANCHURA->name, 'boxed');
      $tipoMenu = $data->tipo_menu ?: session()->get(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, 'full');
      $tipoTarjeta = $data->tipo_tarjeta ?: session()->get(ClaveSesion::UI_TIPO_TARJETAS->name, 'border');

      session()->set(ClaveSesion::UI_TEMA->name, $tema);
      session()->set(ClaveSesion::UI_COLORES->name, $temaColores);
      session()->set(ClaveSesion::UI_DIRECCION->name, $direccion);
      session()->set(ClaveSesion::UI_POSICION_MENU_NAVEGACION->name, $layout);
      session()->set(ClaveSesion::UI_ANCHURA->name, $container);
      session()->set(ClaveSesion::UI_TIPO_MENU_NAVEGACION->name, $tipoMenu);
      session()->set(ClaveSesion::UI_TIPO_TARJETAS->name, $tipoTarjeta);
    });
  });
});
