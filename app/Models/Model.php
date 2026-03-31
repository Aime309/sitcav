<?php

declare(strict_types=1);

namespace App\Models;

use flight\Container;
use Leaf\Auth;

abstract class Model extends \Leaf\Auth\Model
{
  public function __construct()
  {
    $auth = Container::getInstance()->get(Auth::class);

    $this->db = $auth->db();
    $this->table = static::getTableName();
  }

  protected static abstract function getTableName(): string;

  public function count(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table")
      ->column();
  }
}
