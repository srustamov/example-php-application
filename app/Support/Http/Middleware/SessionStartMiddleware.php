<?php

namespace App\Support\Http\Middleware;

use App\Interfaces\Http\IMiddlewareInterface;
use App\Support\Http\Request;
use Closure;

class SessionStartMiddleware implements IMiddlewareInterface
{
    public function handle(Request $request, Closure $next): Request
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $next($request);
    }
}