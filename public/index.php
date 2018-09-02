<?php

declare(strict_types=1);

$container = require_once sprintf(
    '%s/bootstrap/init.php',
    realpath(__DIR__ . '/..//')
);

try {
    $app = new Core\App($container);

    //------------------------        Routes       ------------------------//

    $app->get('/', [App\Controllers\SmsController::class, 'index']);
    $app->post('messages', [App\Controllers\SmsController::class, 'create']);

    //------------------------       /Routes       ------------------------//

    $app->run();
} catch (\Throwable $t) {
    header('Content-Type: application/json');

    echo json_encode([
        'message' => $t->getMessage(),
        'trace'   => $t->getTrace(),
    ]);
}
