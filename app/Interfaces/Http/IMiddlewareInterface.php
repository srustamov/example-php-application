<?php

namespace App\Interfaces\Http;

use App\Support\Http\Request;
use Closure;

interface IMiddlewareInterface
{
    public function handle(Request $request,Closure $next): Request;
}