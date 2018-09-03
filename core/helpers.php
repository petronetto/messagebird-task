<?php

declare(strict_types=1);

if (!function_exists('container')) {
    /**
     * Get the application container.
     *
     * @return Psr\Container\ContainerInterface
     */
    function container(): Psr\Container\ContainerInterface
    {
        return require base_path('bootstrap/init.php');
    }
}

if (!function_exists('config')) {
    /**
     * Get the application container.
     *
     * @return Core\Config\ConfigInterface
     */
    function config(): Core\Config\ConfigInterface
    {
        return container()->get(Core\Config\ConfigInterface::class);
    }
}

if (!function_exists('storage')) {
    /**
     * Get the application storage.
     *
     * @return Core\Storage\StorageInterface
     */
    function storage(): Core\Storage\StorageInterface
    {
        return container()->get(Core\Storage\StorageInterface::class);
    }
}

if (!function_exists('flash')) {
    /**
     * Create a flash message in application storage.
     *
     * @return void
     */
    function flash(string $message): void
    {
        storage()->set('app_flash_message', $message);
    }
}

if (!function_exists('has_flash')) {
    /**
     * Create a message in application storage.
     *
     * @return bool
     */
    function has_flash(): bool
    {
        if (storage()->has('app_flash_message')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('print_flash')) {
    /**
     * Print some text.
     *
     * @return void
     */
    function print_flash(): void
    {
        echo storage()->get('app_flash_message');
        storage()->delete('app_flash_message');
    }
}

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
     * Get the application base url + the path if provided.
     *
     * @param  string $path
     * @return string
     */
    function base_url(string $path = null): string
    {
        ($path !== '/') ?: $path = null;

        return sprintf(
            '%s:%s/%s',
            config()->get('app.url'),
            config()->get('app.port'),
            $path
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
            '%sassets/%s',
            base_url(),
            $file
        );
    }
}

if (!function_exists('hex_to_str')) {
    /**
     * Convert hex to string.
     *
     * @param  string  $hex
     * @return string
     */
    function hex_to_str(string $hex): string
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i].$hex[$i + 1]));
        }

        return $string;
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
