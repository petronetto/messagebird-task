<?php

declare(strict_types=1);

namespace Core\Exceptions;

class NotAllowedHttpException extends BaseException
{
    /**
     * @param string $method
     * @param string $path
     */
    public function __construct(string $method, string $path = null)
    {
        parent::__construct(sprintf(
            'The method %s %s is not allowed',
            $method,
            $path
        ), 405);
    }
}
