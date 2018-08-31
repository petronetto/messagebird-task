<?php

declare(strict_types=1);

namespace App\Validations\Rules;

class IsPhoneNumber implements RulesInterface
{
    /**
     * @param mixed $input
     * @param array $argument
     * @return boolean
     */
    public function isSatisfiedBy($input, array $argument = []): bool
    {
        return (bool) preg_match('/[\d]{8,}/', $input);
    }
}
