<?php

namespace App\Support\Database;

use App\Exceptions\Database\ConnectionException;
use App\Interfaces\Database\IConnectionInterface;
use App\Support\Config;
use PDO;
use PDOException;

class Connection implements IConnectionInterface
{
    protected static array $connection = [];

    public function __construct(protected Config $config)
    {
        $this->createConnection($this->getDefaultConnectionName());
    }

    public function createConnection(string $name): static
    {
        try {

            if(isset(static::$connection[$name])) {
                return $this;
            }

            static::$connection[$name] = new PDO(
                dsn: $this->config->get("database.$name.driver")
                . ':host=' . $this->config->get("database.$name.host")
                . ';port=' . $this->config->get("database.$name.port")
                . ';dbname=' . $this->config->get("database.$name.database"),
                username: $this->config->get("database.$name.username"),
                password: $this->config->get("database.$name.password"),
            );

            static::$connection[$name]->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            static::$connection[$name]->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_OBJ
            );

        } catch (PDOException $e) {
            throw new ConnectionException($e->getMessage(),$e->getCode(),$e);
        }

        return $this;
    }

    public function getConnection(string $name = null) : PDO
    {
        if (is_null($name)) {
            $name = $this->getDefaultConnectionName();
        }

        if (!array_key_exists($name, static::$connection)) {
            $this->createConnection($name);
        }

        return static::$connection[$name];
    }

    public function __call(string $name, array $arguments)
    {
        return $this->getConnection()->$name(...$arguments);
    }



    public function getDefaultConnectionName() : string
    {
        return 'default';
    }
}