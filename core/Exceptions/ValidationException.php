<?php

declare(strict_types=1);

namespace Core\Exceptions;

class ValidationException extends BaseException
{
    /**
     * @param array $invalidFields
     */
    public function __construct(array $invalidFields)
    {
        $message = sprintf(
            'The following fields are invalid: %s',
            implode($invalidFields, ', ')
        );

        parent::__construct($message, 422);
    }
}
