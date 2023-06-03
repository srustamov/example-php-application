<?php

namespace App\Support\Http\Middleware;

use App\Exceptions\HttpException;
use App\Interfaces\Http\IMiddlewareInterface;
use App\Support\Http\Request;
use Closure;

class RateLimiterMiddleware implements IMiddlewareInterface
{
    public function __construct(
        private readonly int    $limit,
        private readonly int    $interval,
        private readonly string $storageKey
    )
    {
    }

    public function handle(Request $request, Closure $next) : Request
    {
        $requestData = $_SESSION[$this->storageKey] ??  [
            'count' => 0,
            'timestamp' => time()
        ];

        $requestCount = $requestData['count'];

        $lastRequestTimestamp = $requestData['timestamp'];

        $elapsedTime = time() - $lastRequestTimestamp;

        if ($elapsedTime > $this->interval) {
            $requestCount = 0;
        }

        if ($requestCount >= $this->limit) {
            throw new HttpException(429,'Too many requests');
        }

        $_SESSION[$this->storageKey] = [
            'count' => $requestCount + 1,
            'timestamp' => time()
        ];

        return $next($request);
    }
}
