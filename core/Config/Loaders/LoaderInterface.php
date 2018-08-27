<?php

declare(strict_types=1);

namespace Core\Config\Loaders;

interface LoaderInterface
{
    public function parse(): array;
}
