<?php

namespace App\Support\Database\Attributes\Events;


use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_METHOD)]
class Event
{
    public function __construct(
        #[ExpectedValues(['created'])]
        public string $event
    )
    {
    }

    public function getName(): string
    {
        return $this->event;
    }
}