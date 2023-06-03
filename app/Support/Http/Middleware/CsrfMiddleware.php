<?php

namespace App\Support\Http\Middleware;

use App\Exceptions\CsrfException;
use App\Interfaces\Http\IMiddlewareInterface;
use App\Support\CSRF;
use App\Support\Http\Request;
use Closure;

final class CsrfMiddleware implements IMiddlewareInterface
{
    public function handle(Request $request, Closure $next): Request
    {
        if ($request->getMethod() === 'POST') {
            if(!CSRF::verifyToken($request->post('_token'))) {
                throw new CsrfException;
            }
        }

        return $next($request);
    }
}