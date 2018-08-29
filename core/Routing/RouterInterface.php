<?php

declare(strict_types=1);

namespace Core\Routing;

interface RouterInterface
{
    /**
     * @param string $route
     * @param array $handler
     * @param array $methods
     * @return void
     */
    public function addRoute(string $route, array $handler, array $methods = ['GET']): void;

    /**
     * @param  string $method
     * @param  string $uri
     * @return array
     */
    public function dispatch(string $method, string $uri): array;
}
