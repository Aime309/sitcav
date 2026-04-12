<?php

declare(strict_types=1);

namespace Leaf {
  if (!function_exists('_env')) {
    /** Gets the value of an environment variable. */
    function _env(string $key, mixed $default = null): mixed
    {
      $env = array_merge(getenv() ?? [], $_ENV ?? []);
      $value = $env[$key] ??= null;

      if ($value === null) {
        return $default;
      }

      switch (strtolower($value)) {
        case 'true':
        case '(true)':
          return true;

        case 'false':
        case '(false)':
          return false;

        case 'empty':
        case '(empty)':
          return '';

        case 'null':
        case '(null)':
          return null;
      }

      if (strpos($value, '"') === 0 && strpos($value, '"') === strlen($value) - 1) {
        return substr($value, 1, -1);
      }

      return $value;
    }
  }
}

namespace {
}
