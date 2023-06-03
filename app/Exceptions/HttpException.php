<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
    public function __construct(
        private readonly int $statusCode = 400,
        protected            $message = '',
        protected            $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}