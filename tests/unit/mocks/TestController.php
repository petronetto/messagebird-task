<?php

declare(strict_types=1);

namespace Tests\Mocks;

class TestController
{
    public function __invoke(string $id, string $num)
    {
        return [$id, $num];
    }
}
