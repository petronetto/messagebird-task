<?php

declare(strict_types=1);

namespace Tests\Mocks;

class ClassB
{
    public $classA;

    public function __construct(ClassA $classA)
    {
        $this->classA = $classA;
    }
}
