<?php

declare(strict_types=1);

namespace App\Validations;

use App\Validations\Rules\RulesInterface;
use ReflectionCLass;

class Validator
{
    /** @var array */
    protected $rules = [];

    /** @var mixed */
    protected $input;

    public function __call($method, $argument)
    {
        $this->rules[] = [
            'object'   => $this->getRule($method),
            'argument' => $argument,
        ];

        return $this;
    }

    /**
     * @param  mixed     $input
     * @return Validator
     */
    public function withInput($input): Validator
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->rules as $rule) {
            if (!$rule['object']->isSatisfiedBy($this->input, $rule['argument'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  string $rule
     * @return void
     */
    protected function getRule(string $rule): RulesInterface
    {
        $namespace = (new ReflectionCLass(get_class($this)))->getNamespaceName();

        $rule = sprintf('\%s\Rules\%s', $namespace, ucfirst($rule));

        return new $rule;
    }
}
