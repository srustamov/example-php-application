<?php

namespace App\Support\Database;

use App\Exceptions\Database\MassAssignmentException;
use App\Interfaces\Database\IConnectionInterface;
use App\Support\Container;
use App\Support\Database\Attributes\Events\Event;
use PDO;
use ReflectionClass;

class Model
{
    public ?int $id = null;

    protected string $connection = 'default';

    public static function find($id)
    {
        $table = (new static)->getTable();

        $statement = static::getConnection()->prepare("SELECT * FROM $table WHERE id = ?");

        $statement->execute([$id]);

        $result = $statement->fetchObject(static::class);

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getTable()
    {
        $reflection = new ReflectionClass($this);

        $attributes = $reflection->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === Attributes\Table::class) {
                return $attribute->newInstance()->getName();
            }
        }

        return strtolower((new ReflectionClass($this))->getShortName()) . 's';
    }

    public static function getConnection(): PDO
    {
        return Container::getInstance()->get(IConnectionInterface::class)->getConnection(
            (new static)->getConnectionName()
        );
    }

    private function getConnectionName(): string
    {
        return $this->connection;
    }

    public function save(): bool
    {
        $data = [];

        $reflection = new ReflectionClass($this);

        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes();
            foreach ($attributes as $attribute) {
                if ($attribute->getName() === Attributes\Column::class) {
                    if (!$property->isPublic()) {
                        throw new MassAssignmentException($property->getName(), static::class);
                    }
                    $data[$property->getName()] = $this->{$property->getName()};
                }
            }
        }

        $columns = implode(',', array_keys($data));

        $values = array_values($data);

        $table = $this->getTable();

        if (!$this->id) {
            $sql = "INSERT INTO $table ($columns) VALUES (" . str_repeat('?,', count($data) - 1) . "?)";
        } else {
            $sql = "UPDATE $table SET ";
            foreach ($data as $column => $value) {
                $sql .= "$column = ?,";
            }
            $sql = rtrim($sql, ',');
            $sql .= " WHERE id = ?";
            $values[] = $this->id;
        }

        $statement = static::getConnection()->prepare($sql);

        if (!$statement->execute($values)) {
            return false;
        }

        if (!$this->id) {
            $this->id = static::getConnection()->lastInsertId();
            $this->fireRegisteredEvents('created');
        }

        return true;
    }

    protected function fireRegisteredEvents(string $name): void
    {
        $class = new ReflectionClass(static::class);

        $methods = $class->getMethods();

        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            foreach ($attributes as $attribute) {
                if ($attribute->getName() === Event::class) {
                    if ($attribute->newInstance()->getName() === $name) {
                        $this->{$method->getName()}($this);
                    }
                }
            }
        }
    }
}