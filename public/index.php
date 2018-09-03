<?php

declare(strict_types=1);

$container = require_once sprintf(
    '%s/bootstrap/init.php',
    realpath(__DIR__ . '/..//')
);

try {
    $app = new Core\App($container);

    //------------------------        Routes       ------------------------//

    $app->get('/', [App\Controllers\HomeController::class, 'index']);
    $app->get('sms', [App\Controllers\SmsController::class, 'index']);
    $app->post('sms/create', [App\Controllers\SmsController::class, 'create']);
    $app->get('sms/list', [App\Controllers\SmsController::class, 'getList']);
    $app->get('sms/:id', [App\Controllers\SmsController::class, 'getSMSDetails']);

    //------------------------       /Routes       ------------------------//

    $app->run();
} catch (\Throwable $t) {
    header('Content-Type: application/json');
    http_response_code($t->getCode());

    echo json_encode([
        'message' => $t->getMessage(),
        'trace'   => $t->getTrace(),
    ]);
}
