<?php

declare(strict_types=1);

if (!function_exists('base_path')) {
    /**
     * Get the application base path.
     *
     * @param  string $path
     * @return string
     */
    function base_path(string $path = null): string
    {
        $path = sprintf('%s/%s', realpath(__DIR__ . '/../'), $path);

        return str_replace('//', '/', $path);
    }
}

if (!function_exists('base_url')) {
    /**
     * Get the application base url.
     *
     * @return string
     */
    function base_url(): string
    {
        return sprintf(
            '%s:%s',
            getenv('APP_URL'),
            getenv('APP_PORT')
        );
    }
}

if (!function_exists('assets')) {
    /**
     * Get the application path for assets.
     *
     * @param  string $path
     * @return string
     */
    function assets(string $file): string
    {
        return sprintf(
            '%s/assets/%s',
            base_url(),
            $file
        );
    }
}

if (!function_exists('app_log')) {
    /**
     * A simple logger.
     *
     * @param  string $message
     * @return void
     */
    function app_log(string $message): void
    {
        $message = sprintf(
            '[%s] Log: %s',
            date('Y-m-d H:i:s'),
            $message
        );

        $file = base_path('/logs/app/application.log');

        if (!file_exists($file)) {
            mkdir(dirname($file), 0755, true);
        }

        file_put_contents($file, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
