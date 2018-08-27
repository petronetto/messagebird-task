<?php

declare(strict_types=1);

namespace Core\Config\Loaders;

use DirectoryIterator;
use SplFileInfo;

class ArrayLoader implements Loader
{
    /** @var string */
    protected $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Parse the configs using a given loader.
     *
     * @return array
     */
    public function parse(): array
    {
        $parsed   = [];
        $iterator = $this->getIterator();

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }

            [$pathname, $filename] = $this->getPathAndFileName($fileInfo);

            $parsed[$filename] = require $pathname;
        }

        return $parsed;
    }

    /**
     * @return DirectoryIterator
     */
    protected function getIterator(): DirectoryIterator
    {
        return new DirectoryIterator(realpath($this->path));
    }

    /**
     * @param  SplFileInfo $fileInfo
     * @return array
     */
    protected function getPathAndFileName(SplFileInfo $fileInfo): array
    {
        return [
            $fileInfo->getPathname(),
            current(explode('.php', $fileInfo->getFilename()))
        ];
    }
}
