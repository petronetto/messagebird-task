<?php

declare(strict_types=1);

require sprintf('%s/vendor/autoload.php', realpath(__DIR__ . '/..//'));

use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Config\Loaders\ArrayLoader;
use Core\Container\Container;
use Core\Http\Response;
use Core\Http\ResponseInterface;
use Core\Routing\Router;
use Core\Routing\RouterInterface;
use Core\Storage\FileStorage;
use Core\Storage\StorageInterface;
use Psr\Container\ContainerInterface;

$container = new Container;

$container->share(ConfigInterface::class, function () {
    $config = new Config;
    $arrayLoader = new ArrayLoader(base_path('config'));
    $config->load([$arrayLoader]);

    return $config;
});

// Binding the interfaces to the concrete implamentations
$container->set(RouterInterface::class, function () {
    return new Router;
});

$container->set(ResponseInterface::class, function () {
    return new Response;
});

$container->set(StorageInterface::class, function () {
    return new FileStorage(base_path());
});

// Creating alias
$container->set('config', function (ContainerInterface $c) {
    return $c->get(ConfigInterface::class);
});

$container->set('router', function (ContainerInterface $c) {
    return $c->get(RouterInterface::class);
});

$container->set('response', function (ContainerInterface $c) {
    return $c->get(ResponseInterface::class);
});

$container->set('storage', function (ContainerInterface $c) {
    return $c->get(StorageInterface::class);
});

return $container;
