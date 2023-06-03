<?php

namespace App\Support\Http;

use App\Interfaces\Http\IMiddlewareInterface;
use Closure;

class MiddlewareHandler
{
    public function __construct(private readonly array $middlewares = [])
    {
    }

    public function handle(Request $request): Request
    {
        $next = function ($request) {
            return $request;
        };

        foreach ($this->middlewares as $middleware) {
            $request = $this->createMiddlewareClosure($middleware, $next)($request);
        }

        return $next($request);
    }

    private function createMiddlewareClosure(IMiddlewareInterface $middleware, $next): Closure
    {
        return function ($request) use ($middleware, $next) {
            return $middleware->handle($request, $next);
        };
    }
}
