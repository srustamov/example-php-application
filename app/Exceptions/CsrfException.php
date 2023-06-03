<?php

namespace App\Exceptions;

class CsrfException extends HttpException
{
    public function __construct()
    {
        parent::__construct(419, 'CSRF token mismatch');
    }
}