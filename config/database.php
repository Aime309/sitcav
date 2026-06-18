<?php

declare(strict_types=1);

foreach (glob("database/migrations/*") ?: [] as $migration) {
  require_once $migration;
}

$reflectionObject = new ReflectionObject(auth()->db());
$reflectionObject->getProperty("errors")->setValue(auth()->db(), []);

auth()->clearErrors();
