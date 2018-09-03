<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Exceptions\NotAllowedHttpException;
use Core\Exceptions\NotFoundHttpException;

class Router implements RouterInterface
{
    /** @var array */
    protected $routes = [];

    /**
     * @param  string $route
     * @param  array  $handler
     * @param  array  $methods
     * @return void
     */
    public function addRoute(string $route, array $handler, array $methods = ['GET']): void
    {
        // Escaping forward slashes and trim the first one
        $route = preg_replace('/\//', '\\/', ltrim($route, '/'));

        // Convert variables e.g. :userId
        $route = preg_replace('/\:([a-z]+)\/*/i', '(?P<\1>[\w]+)\/*', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = sprintf('/^\/*%s$/i', $route);

        $this->routes[$route] = [
            'handler' => $handler,
            'methods' => $methods,
        ];
    }

    /**
     * @param  string                  $method
     * @param  string                  $uri
     * @return array
     * @throws NotFoundHttpException
     * @throws NotAllowedHttpException
     */
    public function dispatch(string $method, string $uri): array
    {
        $route = $this->match($uri);

        if (!$route) {
            throw new NotFoundHttpException($uri);
        }

        if (!in_array($method, $this->routes[$route]['methods'])) {
            throw new NotAllowedHttpException($method, $uri);
        }

        return $this->routes[$route];
    }

    /**
     * @param  string      $url
     * @return string|null
     */
    protected function match(string $url): ?string
    {
        foreach ($this->routes as $route => $handler) {
            if (preg_match($route, $url, $matches)) {
                $this->extractParams($matches, $route);

                return $route;
            }
        }

        return null;
    }

    /**
     * @param  array  $matches
     * @param  string $route
     * @return void
     */
    protected function extractParams(array $matches, string $route): void
    {
        $this->routes[$route]['params'] = [];

        foreach ($matches as $key => $match) {
            if (is_string($key)) {
                $this->routes[$route]['params'][] = $matches[$key];
            }
        }
    }
}
