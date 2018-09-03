<?php

return [
    'name'     => getenv('APP_NAME') ?: 'App',
    'env'      => getenv('APP_ENV') ?: 'prod',
    'key'      => getenv('APP_KEY') ?: 'somethingreallylong',
    'url'      => getenv('APP_URL') ?: 'http://localhost',
    'port'     => getenv('APP_PORT') ?: 8080,
    'isprod'   => (function () {
        $prodNames = ['prd', 'prod', 'production'];
        $appEnv    = getenv('APP_ENV') ?: 'prod';
        if (in_array($appEnv, $prodNames)) {
            return true;
        }
        return false;
    })(),
];
