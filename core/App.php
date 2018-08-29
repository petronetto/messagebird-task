<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Response;
use Core\Http\ResponseInterface;
use Core\Routing\Router;
use Core\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class App
{
    /** @var ContainerInterface */
    protected $container;

    /** @var RouterInterface */
    protected $router;

    /** @var ResponseInterface */
    protected $response;

    /**
     * @param ContainerInterface $container
     * @param RouterInterface $router
     * @param ResponseInterface $response
     */
    public function __construct(
        ContainerInterface $container,
        RouterInterface $router = null,
        ResponseInterface $response = null
    ) {
        $this->container = $container;

        if (!$this->router) {
            $this->container->share('router', function (ContainerInterface $c) {
                return $c->get(Router::class);
            });

            $router = $this->container->get('router');
        }

        $this->router = $router;

        if (!$this->response) {
            $this->container->share('response', function (ContainerInterface $c) {
                return $c->get(Response::class);
            });

            $response = $this->container->get('response');
        }

        $this->response = $response;
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function get(string $uri, array $handler): void
    {
        $this->router->addRoute($uri, $handler, ['GET']);
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function post(string $uri, array $handler): void
    {
        $this->router->addRoute($uri, $handler, ['POST']);
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function put(string $uri, array $handler): void
    {
        $this->router->addRoute($uri, $handler, ['PUT']);
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function patch(string $uri, array $handler): void
    {
        $this->router->addRoute($uri, $handler, ['PATCH']);
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @return void
     */
    public function delete(string $uri, array $handler): void
    {
        $this->router->addRoute($uri, $handler, ['DELETE']);
    }

    /**
     * @param  string $uri
     * @param  array  $handler
     * @param  array  $methods
     * @return void
     */
    public function map(string $uri, array $handler, array $methods): void
    {
        $this->router->addRoute($uri, $handler, $methods);
    }

    /**
     * Run the application.
     * @return void
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';
        $uri    = current(explode('?', $uri));

        $route = $this->router->dispatch($method, $uri);

        $response = $this->process(
            $route['handler'][0],
            $route['handler'][1],
            $route['params']
        );

        if (is_array($response)) {
            $response = ($this->container->get('response'))->withJson($response);
        }

        if (!$response instanceof ResponseInterface) {
            $response = ($this->container->get('response'))->setBody($response);
        }

        $this->respond($response);
    }

    /**
     * @param  string $className
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    protected function process(string $className, string $method, array $params = [])
    {
        $handler = $this->container->get($className);

        return call_user_func_array([$handler, $method], $params);
    }

    /**
     * @param  ResponseInterface $response
     * @return void
     */
    protected function respond(ResponseInterface $response)
    {
        header(
            sprintf(
                'HTTP/%s %s %s',
                '1.1',
                $response->getStatusCode(),
                ''
            )
        );

        foreach ($response->getHeaders() as $header) {
            header("{$header[0]}: $header[1]");
        }

        echo $response->getBody();
    }
}
