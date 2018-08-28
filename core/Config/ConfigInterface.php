<?php

declare(strict_types=1);

namespace Core\Config;

interface ConfigInterface
{
    /**
     * @param array $loaders
     * @return Config
     */
    public function load(array $loaders): Config;

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);
}
