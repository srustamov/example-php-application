<?php

namespace App\Support;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;

class Container
{
    private static ?Container $instance = null;
    private array $bindings = [];
    private array $instances = [];

    public function bind($abstract, $concrete = null): void
    {
        if (is_object($concrete)) {
            static::getInstance()->instances[$abstract] = $concrete;
            return;
        }

        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        static::getInstance()->bindings[$abstract] = $concrete;
    }

    public static function getInstance(): ?Container
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @throws ReflectionException
     */
    public function get(string $id)
    {
        return static::getInstance()->resolve($id);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolve($abstract)
    {
        if (isset(static::getInstance()->instances[$abstract])) {
            $concrete = static::getInstance()->instances[$abstract];
            if ($concrete instanceof Closure) {
                return static::getInstance()->instances[$abstract] = $concrete();
            }
            return $concrete;
        }

        if (isset(static::getInstance()->bindings[$abstract])) {

            $concrete = static::getInstance()->bindings[$abstract];

            $object = static::getInstance()->build($concrete);

            static::getInstance()->instances[$abstract] = $object instanceof Closure ? $object() : $object;

            return $object;
        }

        throw new Exception("Unable to resolve dependency: $abstract");
    }

    /**
     * @throws ReflectionException
     */
    private function build($concrete)
    {
        $reflector = new ReflectionClass($concrete);

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $resolvedDependencies = [];

        foreach ($dependencies as $dependency) {
            if (!$dependency->getType() || !class_exists($dependency->getType()->getName())) {
                $resolvedDependencies[] = $dependency->getDefaultValue();
                continue;
            }

            $resolvedDependencies[] = static::getInstance()->resolve($dependency->getType()->getName());
        }

        return $reflector->newInstanceArgs($resolvedDependencies);
    }

    public function has(string $id): bool
    {
        return isset(static::getInstance()->bindings[$id]);
    }
}
