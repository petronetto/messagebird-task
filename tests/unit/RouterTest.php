<?php

declare(strict_types=1);

namespace Tests;

use Core\Exceptions\NotFoundHttpException;
use Core\Exceptions\NotAllowedHttpException;
use Core\Routing\Router;
use Tests\Mocks\TestController;

class RouterTest extends BaseTest
{
    public function test_add_route()
    {
        $router = new Router;

        $router->addRoute('', [TestController::class,'index']);
        $router->addRoute('some/:id', [TestController::class,'index']);

        $expected = [
            '/^\/*$/i' => [
                'handler' => [
                    'Tests\Mocks\TestController',
                    'index',
                ],
                'methods' => ['GET'],
            ],
            '/^\/*some\/(?P<id>[\w]+)\/*$/i' => [
                'handler' => [
                    'Tests\Mocks\TestController',
                    'index',
                ],
                'methods' => ['GET'],
            ],
        ];

        $this->assertAttributeEquals($expected, 'routes', $router);
    }

    public function test_dispatch()
    {
        $router = new Router;

        $router->addRoute('/', [TestController::class,'index']);
        $router->addRoute('/some/:id', [TestController::class,'index']);

        $expected = [
            'handler' => [
                'Tests\Mocks\TestController',
                'index',
            ],
            'methods' => ['GET'],
        ];

        $handler = $router->dispatch('GET', '/');

        $this->assertEquals($expected, $handler);

        $expected = [
            'handler' => [
                'Tests\Mocks\TestController',
                'index',
            ],
            'methods' => ['GET'],
            'params'  => ['123'],
        ];

        $handler = $router->dispatch('GET', '/some/123');

        $this->assertEquals($expected, $handler);
    }

    public function test_not_found_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        (new Router)->dispatch('GET', '/notexists');
    }

    public function test_not_allowed_exception()
    {
        $this->expectException(NotAllowedHttpException::class);

        $router = new Router;

        $router->addRoute('/', [TestController::class,'index']);

        $router->dispatch('POST', '/');
    }
}
