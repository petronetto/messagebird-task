<?php

declare(strict_types=1);


require sprintf('%s/vendor/autoload.php', realpath(__DIR__ . '/..//'));

$config = new \Core\Config\Config;
$arrayLoader = new \Core\Config\Loaders\ArrayLoader(realpath(__DIR__ . '/../config'));

$config->load([$arrayLoader]);

dump($config->get('app.name'));
dump($config->get('app.isprod'));

