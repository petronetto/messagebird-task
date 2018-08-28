<?php

declare(strict_types=1);

namespace Core\Exceptions;

class NotFoundHttpException extends BaseException
{
    /**
     * @param string $path
     */
    public function __construct(string $path = null)
    {
        parent::__construct(sprintf(
            'The requested url %s was not found',
            $path
        ), 404);
    }
}
