<?php

declare(strict_types=1);

namespace Core\Exceptions;

class InvalidArgumentException extends BaseException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}
