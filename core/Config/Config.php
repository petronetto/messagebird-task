<?php

declare(strict_types=1);

namespace Core\Config;

use Core\Config\Loaders\LoaderInterface;

class Config implements ConfigInterface
{
    /** @var array */
    protected $items = [];

    /** @var array */
    protected $cache = [];

    /**
     * @param array $loaders
     * @return Config
     */
    public function load(array $loaders): Config
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof LoaderInterface) {
                continue;
            }

            $this->items = array_merge($this->items, $loader->parse());
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        return $this->addToCache(
            $key,
            $this->extractFromConfig($key) ?? $default
        );
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    protected function extractFromConfig(string $key)
    {
        $filtered = $this->items;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return;
        }

        return $filtered;
    }

    /**
     * @param string $key
     * @return boolean
     */
    protected function existsInCache(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function fromCache(string $key)
    {
        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function addToCache(string $key, $value)
    {
        $this->cache[$key] = $value;

        return $value;
    }

    /**
     * @param array $items
     * @param string $key
     * @return boolean
     */
    protected function exists(array $items, string $key): bool
    {
        return array_key_exists($key, $items);
    }
}
