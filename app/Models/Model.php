<?php

declare(strict_types=1);

namespace App\Models;

use flight\Container;
use JsonSerializable;
use Leaf\Auth;
use PDOStatement;

abstract class Model extends \Leaf\Auth\Model implements JsonSerializable
{
  public function __construct()
  {
    $this->db = Container::getInstance()->get(Auth::class)->db();
    $this->table = static::getTableName();
  }

  protected static abstract function getTableName(): string;

  final public function count(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table")
      ->column();
  }

  /** @return array<int, static> */
  final public function all(): array
  {
    $rows = $this
      ->db
      ->query("SELECT * FROM $this->table")
      ->all();

    return array_map(function (array $rows): static {
      $model = new static;

      foreach ($rows as $column => $value) {
        $model->$column = $value;
      }

      return $model;
    }, $rows);
  }

  final public function jsonSerialize(): array
  {
    return $this->dataToSave;
  }

  final public function create(array $data): ?PDOStatement
  {
    return $this->db->insert($this->table)->params($data)->execute();
  }
}
