<?php

declare(strict_types=1);

namespace Core\Config\Loaders;

interface Loader
{
    public function parse(): array;
}
