<?php

namespace App\Support\Http;
class Request
{
    public function __construct(
        public array  $query = [],
        public array  $request = [],
        public array  $server = [],
        public array  $files = [],
        public array  $cookies = [],
        public array  $headers = [],
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_SERVER,
            $_FILES,
            $_COOKIE,
            getallheaders(),
        );
    }

    public function get($key, $default = null)
    {
        return $this->query[$key]
            ?? $this->request[$key]
            ?? $default;
    }

    public function post($key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    public function getServer(): array
    {
        return $this->server;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMethod(): string
    {
        $method =  $this->server['REQUEST_METHOD'];

        if ($method === 'POST' && array_key_exists('_method', $this->request)) {
            $method = strtoupper($this->request['_method']);
        }

        return $method;
    }

    public function getPath(): string
    {
        return $this->server['REQUEST_URI'];
    }

    public function getProtocol(): string
    {
        return $this->server['SERVER_PROTOCOL'];
    }

    public function getScheme(): string
    {
        return $this->server['REQUEST_SCHEME'];
    }

    public function getHost(): string
    {
        return $this->server['HTTP_HOST'];
    }

    public function getPort(): string
    {
        return $this->server['SERVER_PORT'];
    }

    public function getRemoteAddress(): string
    {
        return $this->server['REMOTE_ADDR'];
    }

    public function getRemoteHost(): string
    {
        return $this->server['REMOTE_HOST'];
    }

    public function getRemotePort(): string
    {
        return $this->server['REMOTE_PORT'];
    }

    public function getRemoteUser(): string
    {
        return $this->server['REMOTE_USER'];
    }
}