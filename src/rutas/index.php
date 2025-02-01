<?php

foreach (glob(__DIR__ . '/_*.php') as $rutas) {
  require $rutas;
}
