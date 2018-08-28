<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends BaseException implements NotFoundExceptionInterface
{
    /**
     * @param string $className
     */
    public function __construct(string $className = null)
    {
        parent::__construct(sprintf(
            'The requested class %s was not found',
            $className
        ), 400);
    }
}
