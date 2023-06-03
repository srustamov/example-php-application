<?php

namespace App\Support;

use App\Support\Http\Request;
use App\Support\Http\Response;
use Closure;
use Exception;

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
        'OPTIONS' => [],
        'HEAD' => [],
    ];

    protected null|string|Closure $fallback = null;

    public function post($uri, $controller): void
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function resolve(string $uri, string $method): Response
    {
        if ($method === 'POST' && array_key_exists('_method', $_POST)) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = $uri === '' ? '/' : $uri;

        return $this->direct($uri, $method);
    }

    /**
     * @throws Exception
     */
    public function direct($uri, $method) : Response
    {
        if (array_key_exists($uri, $this->routes[$method])) {

            $handler = $this->routes[$method][$uri];

            if ($handler instanceof Closure) {
                return new Response(call_user_func_array($handler, [
                    Container::getInstance()->get(Request::class),
                    Container::getInstance()->get(Response::class),
                ]));
            }

            if (is_array($handler)) {
                return $this->callAction(...$handler);
            }

            return $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
        }

        if ($this->fallback) {
            return new Response(call_user_func_array($this->fallback,[
                Container::getInstance()->get(Request::class),
                Container::getInstance()->get(Response::class),
            ]));
        }

        throw new Exception('No route defined for this URI.');
    }

    public function get($uri, $controller): void
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * @throws Exception
     */
    protected function callAction($controller, $action)
    {
        if (!method_exists($controller, $action)) {
            throw new Exception("{$controller} does not respond to the {$action} action.");
        }

        if (!Container::getInstance()->has($controller)) {
            Container::getInstance()->bind($controller);
        }

        $controller = Container::getInstance()->get($controller);

        $response = call_user_func_array(
            [$controller, $action],
            [
                Container::getInstance()->get(Request::class),
                Container::getInstance()->get(Response::class),
            ]
        );

        if (is_string($response)) {
            $response = new Response($response);
        }

        if (!$response instanceof Response) {
            $response = new Response(json_encode($response));
            $response->setHeader('Content-Type', 'application/json');
        }

        return $response;
    }

    public function fallback(string|Closure $action): void
    {
        $this->fallback = $action;
    }
}