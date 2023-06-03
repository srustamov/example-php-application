<?php

namespace App\Interfaces\Database;

use PDO;

interface IConnectionInterface
{
    public function createConnection(string $name): static;

    public function getConnection(string $name = null): PDO;

    public function getDefaultConnectionName(): string;
}