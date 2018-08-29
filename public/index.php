<?php

$container = require_once sprintf(
    '%s/bootstrap/init.php',
    realpath(__DIR__ . '/..//')
);

try {
    $app = new Core\App(
        $container,
        $container->get('router')
    );

    //------------------------        Routes       ------------------------//
    $app->get('/', [App\Controllers\MessageController::class, 'index']);
    $app->get('messages/:msg', [App\Controllers\MessageController::class, 'index']);

    //------------------------       /Routes       ------------------------//

    $app->run();
} catch (\Throwable $t) {
    echo $t->getMessage();
}
