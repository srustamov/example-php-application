<?php

namespace App\Support\Http\Middleware;

use App\Interfaces\Http\IMiddlewareInterface;
use App\Support\Http\Request;
use Closure;

class TrimStringAndEmptyStringConvertNullMiddleware implements IMiddlewareInterface
{

    public function handle(Request $request, Closure $next): Request
    {
        foreach ($request->getQuery() as $key => $value) {
            $request->query[$key] = trim($value) === '' ? null : trim($value);
        }

        foreach ($request->getRequest() as $key => $value) {
            $request->request[$key] = trim($value) === '' ? null : trim($value);
        }

        return $next($request);
    }
}