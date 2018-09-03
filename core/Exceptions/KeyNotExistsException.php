<?php

declare(strict_types=1);

namespace Core\Exceptions;

class KeyNotExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('The given key does not exists', 400);
    }
}
