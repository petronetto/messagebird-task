<?php

declare(strict_types=1);

namespace App\Exceptions;

use Core\Exceptions\BaseException;

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
