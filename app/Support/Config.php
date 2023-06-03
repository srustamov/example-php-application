<?php

namespace App\Support;

class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'database' => [
                'default' => [
                    'driver' => $env['DB_CONNECTION'] ?? 'mysql',
                    'host' => $env['DB_HOST'] ?? 'localhost',
                    'port' => $env['DB_PORT'] ?? '3306',
                    'database' => $env['DB_DATABASE'] ?? '',
                    'username' => $env['DB_USERNAME'] ?? 'root',
                    'password' => $env['DB_PASSWORD'] ?? '',
                    'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
                    'collation' => $env['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
                ]
            ]
        ];
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);

        $config = &$this->config;

        foreach ($segments as $segment) {
            if (!isset($config[$segment])) {
                $config[$segment] = [];
            }

            $config = &$config[$segment];
        }

        $config = $value;
    }


    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);

        $config = $this->config;

        foreach ($segments as $segment) {
            if (!isset($config[$segment])) {
                return $default;
            }

            $config = $config[$segment];
        }

        return $config;
    }
}