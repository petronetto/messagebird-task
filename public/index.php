<?php

declare(strict_types=1);

error_reporting(-1);

require sprintf('%s/vendor/autoload.php', realpath(__DIR__ . '/..//'));

$container = new Core\Container\Container;

$container->share('config', function () {
    $config = new Core\Config\Config;
    $arrayLoader = new Core\Config\Loaders\ArrayLoader(realpath(__DIR__ . '/../config'));

    $config->load([$arrayLoader]);

    return $config;
});

$config = $container->get('config');

dump($config->get('app.name'));
dump($config->get('app.isprod'));
