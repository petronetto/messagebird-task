<?php

declare(strict_types=1);

namespace Core\Container;

use Core\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /** @var array */
    protected $items = [];

    /** @var array */
    protected $cache = [];

    /**
     * Resolve the instance and put it in cache.
     * Acts like a singleton.
     *
     * @param  string   $id
     * @param  callable $closure
     * @return void
     */
    public function share(string $id, callable $closure)
    {
        $this->items[$id] = function () use ($id, $closure) {
            if (!$this->isCached($id)) {
                $this->cache[$id] = $closure($this);
            }

            return $this->cache[$id];
        };
    }

    /**
     * @param  string   $id
     * @param  callable $closure
     * @return void
     */
    public function set(string $id, callable $closure): void
    {
        $this->items[$id] = $closure;
    }

    /**
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->items[$id]($this);
        }

        return $this->autowire($id);
    }

    /**
     * @param  string $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->items[$id]);
    }

    /**
     * @param  string $className
     * @return mixed
     *
     * @throws NotFoundException
     */
    protected function autowire(string $className)
    {
        $reflector = $this->getReflector($className);

        if (!$reflector->isInstantiable()) {
            throw new NotFoundException($className);
        }

        if ($constructor = $reflector->getConstructor()) {
            return $reflector->newInstanceArgs(
                $this->getDependencies($constructor)
            );
        }

        return new $className();
    }

    /**
     * @param  string          $className
     * @return ReflectionClass
     *
     * @throws NotFoundException
     */
    protected function getReflector(string $className): ReflectionClass
    {
        if (!class_exists($className)) {
            throw new NotFoundException($className);
        }

        return new ReflectionClass($className);
    }

    /**
     * @param  ReflectionMethod $constructor
     * @return void
     */
    protected function getDependencies(ReflectionMethod $constructor)
    {
        return array_map(function (ReflectionParameter $dependency) {
            return $this->resolveReflectedDependency($dependency);
        }, $constructor->getParameters());
    }

    /**
     * @param  ReflectionParameter $dependency
     * @return mixed
     *
     * @throws NotFoundException
     */
    protected function resolveReflectedDependency(ReflectionParameter $dependency)
    {
        if (is_null($dependency->getClass())) {
            throw new NotFoundException;
        }

        return $this->get($dependency->getClass()->getName());
    }

    /**
     * @param  string  $id
     * @return boolean
     */
    protected function isCached(string $id): bool
    {
        return isset($this->cache[$id]);
    }
}
