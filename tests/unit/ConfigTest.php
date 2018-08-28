<?php

declare(strict_types=1);

namespace Tests;

use Core\Config\Config;
use Core\Config\Loaders\ArrayLoader;

class ConfigTest extends BaseTest
{
    public function test_get(): void
    {
        $loader = new ArrayLoader(realpath(__DIR__ . '/mocks/config'));
        $config = new Config;

        $config->load([$loader]);

        $expected = [
            'app' => [
                'name' => 'test',
            ]
        ];

        $this->assertEquals('test', $config->get('app.name'));
        $this->assertAttributeEquals($expected, 'items', $config);
    }

    public function test_get_from_cache(): void
    {
        $loader = new ArrayLoader(realpath(__DIR__ . '/mocks/config'));
        $config = new Config;

        $config->load([$loader]);

        $expected = ['app.name' => 'test'];

        $this->assertEquals('test', $config->get('app.name'));
        // Must get the value from cahe
        $this->assertEquals('test', $config->get('app.name'));
        $this->assertAttributeEquals($expected, 'cache', $config);
    }
}
