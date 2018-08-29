<?php

declare(strict_types=1);

require sprintf('%s/vendor/autoload.php', realpath(__DIR__ . '/..//'));

use Core\Config\Config;
use Core\Config\Loaders\ArrayLoader;
use Core\Container\Container;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

$container = new Container;

$container->share('config', function () {
    $config = new Config;
    $arrayLoader = new ArrayLoader(realpath(__DIR__ . '/../config'));
    $config->load([$arrayLoader]);

    return $config;
});

$container->share(RouterInterface::class, function () {
    return new Router;
});

$container->share('router', function (ContainerInterface $c) {
    return $c->get(RouterInterface::class);
});

$container->share('response', function () {
    return new Response;
});

return $container;
