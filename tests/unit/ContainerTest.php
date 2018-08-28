<?php

declare(strict_types=1);

namespace Tests;

use Core\Container\Container;
use Core\Exceptions\NotFoundException;
use Tests\Mocks\ClassA;
use Tests\Mocks\ClassB;

class ContainerTest extends BaseTest
{
    public function test_share_and_get()
    {
        $container = new Container;

        $depTest = function () {
            return 'Ok';
        };

        $expected = ['test' => $depTest];

        $container->share('test', $depTest);
        $this->assertAttributeEquals($expected, 'items', $container);
        $this->assertEquals('Ok', $container->get('test'));

        // Checking if the items was cached
        $expected = ['test' => $depTest()];
        $this->assertAttributeEquals($expected, 'cache', $container);
    }

    public function test_set()
    {
        $container = new Container;

        $depTest = function () {
            return 'Ok';
        };

        $expected = ['test' => $depTest];

        $container->set('test', $depTest);
        $this->assertAttributeEquals($expected, 'items', $container);
        $this->assertEquals('Ok', $container->get('test'));

        // Checking if the items wasn't cached
        $this->assertAttributeEquals([], 'cache', $container);
    }

    public function test_autowire()
    {
        $container = new Container;

        $classB = $container->get(ClassB::class);

        $this->assertInstanceOf(ClassB::class, $classB);
        $this->assertInstanceOf(ClassA::class, $classB->classA);
    }

    public function test_autowire_exception()
    {
        $this->expectException(NotFoundException::class);

        $container = new Container;

        $container->get('does_not_exists');
    }
}
