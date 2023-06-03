<?php

namespace App\Support\Database\Attributes;


use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
    public function __construct(
        private readonly string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}