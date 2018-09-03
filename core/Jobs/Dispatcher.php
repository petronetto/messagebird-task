<?php

declare(strict_types=1);

namespace Core\Jobs;

use Core\Storage\FileStorage;

class Dispatcher
{
    /**
     * @param JobInterface $job
     * @return void
     */
    public function dispatch(JobInterface $job): void
    {
        $storage = new FileStorage(base_path(), 'jobs');

        $job = serialize($job);

        $storage->set((string) round(microtime(true) * 1000), $job);
    }
}
