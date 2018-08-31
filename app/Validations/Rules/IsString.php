<?php

declare(strict_types=1);

namespace App\Validations\Rules;

class IsString implements RulesInterface
{
    /**
     * @param mixed $input
     * @param array $argument
     * @return boolean
     */
    public function isSatisfiedBy($input, array $argument = []): bool
    {
        return is_string($input);
    }
}
