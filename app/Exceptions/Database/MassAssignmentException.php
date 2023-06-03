<?php

namespace App\Exceptions\Database;

use RuntimeException;

class MassAssignmentException extends RuntimeException
{
    public function __construct(
        protected string $column,
        protected string $model
    )
    {
        parent::__construct("Column {$column} is not mass assignable in model {$model}");
    }
}