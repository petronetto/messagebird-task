<?php

declare(strict_types=1);

namespace Tests;

use Core\App;
use Core\Container\Container;
use Core\Routing\Router;
use Tests\Mocks\TestController;

class AppTest extends BaseTest
{
    public function test_router(): void
    {
        $router = (new Container)->get(Router::class);

        $app = $this->getApp();

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_get(): void
    {
        list($app, $router) = $this->getVarsForHttpMethods('get');

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_post(): void
    {
        list($app, $router) = $this->getVarsForHttpMethods('post');

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_patch(): void
    {
        list($app, $router) = $this->getVarsForHttpMethods('patch');

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_put(): void
    {
        list($app, $router) = $this->getVarsForHttpMethods('put');

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_delete(): void
    {
        list($app, $router) = $this->getVarsForHttpMethods('delete');

        $this->assertAttributeEquals($router, 'router', $app);
    }

    public function test_map(): void
    {
        $app = $this->getApp();

        $app->map('/some/:id', [TestController::class, 'index'], ['POST', 'GET']);

        $router = (new Container)->get(Router::class);

        $router->addRoute('/some/:id', [TestController::class, 'index'], ['POST', 'GET']);

        $this->assertAttributeEquals($router, 'router', $app);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_run_retuning_response()
    {
        $app = $this->getApp();

        $app->get('/', [TestController::class, 'retrunsResponse']);

        ob_start();
        $app->run();
        $res = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('The answer for everything: 42', $res);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_run_returning_string()
    {
        $app = $this->getApp();

        $app->get('/', [TestController::class, 'retrunsString']);

        ob_start();
        $app->run();
        $res = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('The answer is 42', $res);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_run_returning_json()
    {
        $app = $this->getApp();

        $app->get('/', [TestController::class, 'index']);

        ob_start();
        $app->run();
        $res = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('["42","42"]', $res);
    }

    private function getApp()
    {
        $container = new Container;

        return new App(
            $container,
            $container->get(Router::class)
        );
    }

    public function getVarsForHttpMethods(string $verb)
    {
        $app = $this->getApp();

        $app->{$verb}('/some/:id', [TestController::class, 'index']);

        $router = (new Container)->get(Router::class);

        $router->addRoute('/some/:id', [TestController::class, 'index'], [strtoupper($verb)]);

        return [$app, $router];
    }
}
