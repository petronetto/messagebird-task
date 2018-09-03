<?php

declare(strict_types=1);

namespace Core\Jobs;

interface JobInterface
{
    public function handle(): void;
}
