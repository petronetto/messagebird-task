<?php

declare(strict_types=1);

require sprintf('%s/vendor/autoload.php', realpath(__DIR__ . '/..//'));

use Core\Config\Config;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Container\Container;
use Core\Config\ConfigInterface;
use Core\Http\ResponseInterface;
use Core\Routing\RouterInterface;
use Core\Config\Loaders\ArrayLoader;
use Psr\Container\ContainerInterface;

$container = new Container;

$container->share(ConfigInterface::class, function () {
    $config = new Config;
    $arrayLoader = new ArrayLoader(realpath(__DIR__ . '/../config'));
    $config->load([$arrayLoader]);

    return $config;
});

$container->set(RouterInterface::class, function () {
    return new Router;
});

$container->set(ResponseInterface::class, function () {
    return new Response;
});

$container->set('config', function (ContainerInterface $c) {
    return $c->get(ConfigInterface::class);
});

$container->set('router', function (ContainerInterface $c) {
    return $c->get(RouterInterface::class);
});

$container->set('response', function (ContainerInterface $c) {
    return $c->get(RouterInterface::class);
});

return $container;
