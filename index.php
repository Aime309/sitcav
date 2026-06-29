<?php

declare(strict_types=1);

use flight\Container;
use GuzzleHttp\Psr7\HttpFactory;
use Leaf\Auth;
use Leaf\Config;
use Leaf\Db;
use Leaf\Db\Utils;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Return the leaf auth object
 *
 * @return Leaf\Auth
 */
function auth()
{
  if (!Config::getStatic("auth")) {
    Config::singleton(
      "auth",
      static fn(): Auth => new class extends Auth {
        #[Override]
        public function autoConnect(array $pdoOptions = []): static
        {
          $this->db = new class extends Db {
            #[Override]
            public function execute(): ?PDOStatement
            {
              $this->queryResult = null;

              return parent::execute();
            }

            #[Override]
            public function fetchAssoc(): mixed
            {
              $added = $this->added;
              $hidden = $this->hidden;
              $currentTable = $this->table;
              $hiddenEagerFields = [];

              $this->execute();
              $result = $this->queryResult->fetch(PDO::FETCH_ASSOC);

              if (count($added)) {
                $result = array_merge($result, $added);
              }

              if (count($hidden)) {
                foreach ($hidden as $item) {
                  if (isset($result[$item])) {
                    unset($result[$item]);
                  } elseif (strpos($item, ".") !== false) {
                    $hiddenEagerFields[] = explode(".", $item);
                  }
                }

                $this->hidden = [];
              }

              if (count($this->eager)) {
                foreach ($this->eager as $item) {
                  $keyName = Utils::basicSingularize($item["table"]);

                  if (
                    class_exists(Config::class) &&
                    Config::get("db.table") === $item["table"]
                  ) {
                    $hiddenEagerFields = array_merge(
                      $hiddenEagerFields,
                      Config::get("hidden"),
                    );
                  }

                  if ($result[$item["foreignKey"]] ?? false) {
                    $stmt = $this->connection()->prepare(
                      "SELECT * FROM {$item['table']} WHERE id = ?",
                    );

                    $stmt->execute([$result[$item["foreignKey"]]]);
                    $result[$keyName] = $stmt->fetch(PDO::FETCH_ASSOC);
                  } else {
                    $keyName = $item["table"];
                    $item["foreignKey"] =
                      Utils::basicSingularize($currentTable) . "_id";

                    $stmt = $this->connection()->prepare(
                      "SELECT * FROM {$item['table']} WHERE {$item['foreignKey']} = ?",
                    );

                    $stmt->execute([$result["id"]]);
                    $result[$keyName] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  }

                  if (count($hiddenEagerFields)) {
                    foreach ($hiddenEagerFields as $field) {
                      if (is_array($field) && $field[0] === $keyName) {
                        $field = $field[1];
                      }

                      if (
                        $field === "field.id" &&
                        class_exists(Config::class)
                      ) {
                        $field = Config::get("id.key");
                      }

                      if (
                        $field === "field.password" &&
                        class_exists(Config::class)
                      ) {
                        $field = Config::get("password.key");
                      }

                      unset($result[$keyName][$field]);
                    }
                  }
                }

                $this->eager = [];
              }

              $currentTable = null;

              return $result;
            }
          };

          $this->db->autoConnect($pdoOptions);

          return $this;
        }
      },
    );
  }

  return Config::get("auth");
}

/**
 * Return the database object
 *
 * @param string|null $connection The connection to return db with
 * @return \Leaf\Db
 */
function db(?string $connection = null)
{
  if (!Config::getStatic("db")) {
    Config::singleton("db", auth()->db(...));
  }

  $db = Config::get("db");

  if ($db instanceof Db) {
    return $db->use($connection);
  }
}

require_once "vendor/autoload.php";

$_ENV = (array) require ".env.php";
$_ENV += (array) require ".env.example.php";

Container::getInstance()->singleton(RequestFactoryInterface::class, HttpFactory::class);

foreach (glob("config/*") ?: [] as $config) {
  require_once $config;
}

foreach (glob("routes/*") ?: [] as $routes) {
  require_once $routes;
}

Flight::route("GET /", static fn() => Flight::redirect("/ecommerce"))->setAlias(
  "index",
);

Flight::set("flight.handle_errors", false);
Flight::registerContainerHandler(Container::getInstance());
Flight::start();
